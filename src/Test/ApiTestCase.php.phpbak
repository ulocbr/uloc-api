<?php

namespace Uloc\ApiBundle\Test;

use Uloc\ApiBundle\Entity\User\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Uloc\Bundle\AppBundle\Test\FixturesData;

class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    /**
     * @var array
     */
    private static $history = array();

    private static $isPurge = false;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * @var FormatterHelper
     */
    private $formatterHelper;

    private $responseAsserter;

    public static function setUpBeforeClass(): void
    {
        $handler = HandlerStack::create();

        $handler->push(Middleware::history(self::$history));
        $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
            $path = $request->getUri()->getPath();
            if (strpos($path, '/app_test.php') !== 0) {
                $path = '/app_test.php' . $path;
            }
            $uri = $request->getUri()->withPath($path);

            return $request->withUri($uri);
        }));

        $baseUrl = getenv('TEST_BASE_URL');
        if (!$baseUrl) {
            static::fail('No TEST_BASE_URL environmental variable set in phpunit.xml.');
        }
        self::$staticClient = new Client([
            'base_uri' => $baseUrl,
            'http_errors' => false,
            'handler' => $handler
        ]);

        self::bootKernel();
    }

    protected function setUp($forcePurge = false, $basicData = true)
    {
        $this->client = self::$staticClient;
        // reset the history
        self::$history = array();

        /**
         * A condição abaixo, junto com a propriede estática $isPurge garante que o banco de dados seja limpo somente uma vez
         * O carregamento dos dados básicos (createBasicData) pode ser impedido por meio do bool $basicData
         */
        if (!self::$isPurge || $forcePurge) {
            $this->purgeDatabase();
            if ($basicData) {
                $this->createBasicData();
            }
            self::$isPurge = true;
        }
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        // purposefully not calling parent class, which shuts down the kernel
    }

    protected function onNotSuccessfulTest(\Throwable $e): void
    {
        if ($lastResponse = $this->getLastResponse()) {
            $this->printDebug('');
            $this->printDebug('<error>O teste falhou!</error> ao fazer a seguinte requisição:');
            $this->printLastRequestUrl();
            $this->printDebug('');

            $this->debugResponse($lastResponse);
        }

        throw $e;
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }

    protected function printLastRequestUrl()
    {
        $lastRequest = $this->getLastRequest();

        if ($lastRequest) {
            $this->printDebug(sprintf('<comment>%s</comment>: <info>%s</info>', $lastRequest->getMethod(), $lastRequest->getUri()));
        } else {
            $this->printDebug('Nenhuma requisição foi feita.');
        }
    }

    protected function debugResponse(ResponseInterface $response)
    {
        foreach ($response->getHeaders() as $name => $values) {
            $this->printDebug(sprintf('%s: %s', $name, implode(', ', $values)));
        }
        $this->printDebug('HTTP Response: ' . $response->getStatusCode());

        $body = (string)$response->getBody();

        $contentType = $response->getHeader('Content-Type');
        $contentType = $contentType[0];
        if ($contentType == 'application/json' || strpos($contentType, '+json') !== false) {
            $data = json_decode($body);
            if ($data === null) {
                // invalid JSON!
                $this->printDebug($body);
            } else {
                // valid JSON, print it pretty
                $this->printDebug(json_encode($data, JSON_PRETTY_PRINT));
            }
        } else {
            // the response is HTML - see if we should print all of it or some of it
            $isValidHtml = strpos($body, '</body>') !== false;

            if ($isValidHtml) {
                $this->printDebug('');
                $crawler = new Crawler($body);

                // very specific to Symfony's error page
                $isError = $crawler->filter('#traces-0')->count() > 0
                    || strpos($body, 'looks like something went wrong') !== false;
                if ($isError) {
                    $this->printDebug('Houve um erro!!!!');
                    $this->printDebug('');
                } else {
                    $this->printDebug('Resumo HTML (h1 e h2):');
                }

                // finds the h1 and h2 tags and prints them only
                foreach ($crawler->filter('h1, h2')->extract(array('_text')) as $header) {
                    // avoid these meaningless headers
                    if (strpos($header, 'Stack Trace') !== false) {
                        continue;
                    }
                    if (strpos($header, 'Logs') !== false) {
                        continue;
                    }

                    // remove line breaks so the message looks nice
                    $header = str_replace("\n", ' ', trim($header));
                    // trim any excess whitespace "foo   bar" => "foo bar"
                    $header = preg_replace('/(\s)+/', ' ', $header);

                    if ($isError) {
                        $this->printErrorBlock($header);
                    } else {
                        $this->printDebug($header);
                    }
                }

                /*
                 * When using the test environment, the profiler is not active
                 * for performance. To help debug, turn it on temporarily in
                 * the config_test.yml file (framework.profiler.collect)
                 */
                $profilerUrl = $response->getHeader('X-Debug-Token-Link');
                if ($profilerUrl) {
                    $fullProfilerUrl = $response->getHeader('Host')[0] . $profilerUrl[0];
                    $this->printDebug('');
                    $this->printDebug(sprintf(
                        'Profiler URL: <comment>%s</comment>',
                        $fullProfilerUrl
                    ));
                }

                // an extra line for spacing
                $this->printDebug('');
            } else {
                $this->printDebug($body);
            }
        }
    }

    /**
     * Print a message out - useful for debugging
     *
     * @param $string
     */
    protected function printDebug($string)
    {
        if ($this->output === null) {
            $this->output = new ConsoleOutput();
        }

        $this->output->writeln($string);
    }

    /**
     * Print a debugging message out in a big red block
     *
     * @param $string
     */
    protected function printErrorBlock($string)
    {
        if ($this->formatterHelper === null) {
            $this->formatterHelper = new FormatterHelper();
        }
        $output = $this->formatterHelper->formatBlock($string, 'bg=red;fg=white', true);

        $this->printDebug($output);
    }

    /**
     * @return RequestInterface
     */
    private function getLastRequest()
    {
        if (!self::$history || empty(self::$history)) {
            return null;
        }

        $history = self::$history;

        $last = array_pop($history);

        return $last['request'];
    }

    /**
     * @return ResponseInterface
     */
    private function getLastResponse()
    {
        if (!self::$history || empty(self::$history)) {
            return null;
        }

        $history = self::$history;

        $last = array_pop($history);

        return $last['response'];
    }

    /**
     * @param $username
     * @param string $plainPassword
     * @return User
     */
    protected function createUser($username, $plainPassword = 'teste1')
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username . '@tiagofelipe.com');
        $password = $this->getService('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER', 'ROLE_INTRANET']);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function createBasicData()
    {
        //Purge FixturesData
        FixturesData::purge();
        $em = $this->getEntityManager();
        $fixtures = new FixturesData($em);

        $user = $this->createUser('tiago');
        FixturesData::addDefaultIds('user', $user->getUsername());

        /* Endereço */
        #$pais1 = $fixtures->createPais('Brasil', 'BR');
        #$uf1 = $fixtures->createUf('Minas Gerais', 'MG');
        #$uf2 = $fixtures->createUf('Brasília', 'DF');
        #$municipio1 = $fixtures->createMunicipio("Montes Claros");
        #$municipio2 = $fixtures->createMunicipio("Brasília", $uf2->getId());
        #$bairro1 = $fixtures->createBairro("Edgar Pereira");
        #$bairro2 = $fixtures->createBairro("Aguas Claras", $municipio2->getId());

        #$pais2 = $fixtures->createPais('Estados Unidos', 'USA');

        $tipoFinalidadeEndereco1 = $fixtures->createTipoFinalidadeEndereco('Residencial');
        $tipoFinalidadeEndereco2 = $fixtures->createTipoFinalidadeEndereco('Comercial');

        /* Pessoa */
        $campoExtraPessoa1 = $fixtures->createCampoExtraPessoa('1', 'Cidadania');
        $campoExtraPessoa2 = $fixtures->createCampoExtraPessoa('2', 'Profissão');
        $tipoFinalidadeEmail1 = $fixtures->createTipoFinalidadeEmail('Pessoal');
        $tipoFinalidadeEmail2 = $fixtures->createTipoFinalidadeEmail('Trabalho');
        $tipoFinalidadeTelefone1 = $fixtures->createTipoFinalidadeTelefone('Pessoal');
        $tipoFinalidadeTelefone2 = $fixtures->createTipoFinalidadeTelefone('Trabalho');

        $origemCadastroPessoa = $fixtures->createOrigemCadastroPessoa('Interno');
        $origemCadastroPessoa2 = $fixtures->createOrigemCadastroPessoa('Site');

        return $this;
    }

    protected function getAuthorizedHeaders($username, $headers = array())
    {
        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);

        $headers['Authorization'] = 'Bearer ' . $token;

        return $headers;
    }

    /**
     * @return ResponseAsserter
     */
    protected function asserter()
    {
        if ($this->responseAsserter === null) {
            $this->responseAsserter = new ResponseAsserter();
        }

        return $this->responseAsserter;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * Call this when you want to compare URLs in a test
     *
     * (since the returned URL's will have /app_test.php in front)
     *
     * @param string $uri
     * @return string
     */
    protected function adjustUri($uri)
    {
        return '/app_test.php' . $uri;
    }

    protected function post($url, $data, $authorizedHeaders = true)
    {
        $content = array();
        $content['body'] = \json_encode($data);
        if ($authorizedHeaders) {
            $content['headers'] = $this->getAuthorizedHeaders('tiago');
        }
        return $this->client->post($url, $content);
    }

}
