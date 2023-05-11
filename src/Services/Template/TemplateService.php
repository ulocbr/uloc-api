<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Template;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Uloc\ApiBundle\Entity\App\Template;
use Uloc\ApiBundle\Entity\App\Variable;
use Uloc\ApiBundle\Services\Config\ConfigServiceInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

/**
 * Class TemplateService
 * Serviço para gerenciar templates e variáveis (globais e costumizadas)
 * @TODO Criar TemplateFixedEntity e poder correlacionar uma entidade com um tipo e id fixo de template, para algumas necessidades de templates customizados para um ou determinado grupo de entidades
 * @package Uloc\ApiBundle\Services\Template
 */
class TemplateService
{
    protected $om;
    protected $logger;
    protected $configService;
    protected $security;
    public static $convertersCache = [];
    public static $twigExtensions = [];

    public function __construct(ObjectManager $om, LogInterface $logger, ConfigServiceInterface $configService, Security $security)
    {
        $this->om = $om;
        $this->logger = $logger;
        $this->configService = $configService;
        $this->security = $security;
    }

    /**
     * @param string $code
     * @return object|null
     */
    public function getTemplateByCode(string $code)
    {
        return $this->om->getRepository(Template::class)->findOneBy(['code' => $code, 'active' => true], ['id' => 'ASC']);
    }

    /**
     * @TODO: IF CASE or IMPLEMENT TWIG?
     * @param $template
     * @param array $dataToParse
     * @param array $converters - Array of classess implements VariableConversorInterface or callback of converters
     * @param string[] $oderPriority
     * @throws \Exception
     */
    public function proccessTemplate($template, array $dataToParse = [], array $converters = [], $oderPriority = ['custom', 'internal'], $useFake = false)
    {

        if (!($template instanceof Template)) {
            $template = $this->getTemplateByCode($template);
            if (!$template) {
                throw new \Exception(sprintf('Template %s does not exists', $template));
            }
        }

        $template = clone $template; // to prevent edit entity in flush

        $subject = $template->getSubject();
        $document = $template->getTemplate();
        $puretext = $template->getPureText();

        $templateForEmail = $template->getEmail();
        $templateForPdf = $template->getPdf();
        $templateForExcel = $template->getExcel();
        $templateForCsv = $template->getCsv();
        $templateForTxt = $template->getTxt();
        $templateForPrinter = $template->getPrinter();

        // Extract all vars from template
        $subjectVars = self::extractTemplateVars($subject);
        $documentVars = self::extractTemplateVars($document);
        $pureTextVars = self::extractTemplateVars($puretext);

        $templateForEmailVars = self::extractTemplateVars($templateForEmail);
        $templateForPdfVars = self::extractTemplateVars($templateForPdf);
        $templateForExcelVars = self::extractTemplateVars($templateForExcel);
        $templateForCsvVars = self::extractTemplateVars($templateForCsv);
        $templateForTxtVars = self::extractTemplateVars($templateForTxt);
        $templateForPrinterVars = self::extractTemplateVars($templateForPrinter);

        $allVars = array_values(array_unique(array_merge(
            $documentVars,
            $pureTextVars,
            $subjectVars,

            $templateForEmailVars,
            $templateForPdfVars,
            $templateForExcelVars,
            $templateForCsvVars,
            $templateForTxtVars,
            $templateForPrinterVars
        )));

        $loader = new \Twig\Loader\ArrayLoader([
            'document' => $document,
            'subject' => $subject,
            'puretext' => $puretext,
            'templateForEmail' => $templateForEmail,
            'templateForPdf' => $templateForPdf,
            'templateForExcel' => $templateForExcel,
            'templateForCsv' => $templateForCsv,
            'templateForTxt' => $templateForTxt,
            'templateForPrinter' => $templateForPrinter,
        ]);
        $twig = new \Twig\Environment($loader);

        if (count(self::$twigExtensions)) {
            foreach (self::$twigExtensions as $extension) {
                $twig->addExtension($extension);
            }
        }

        // Check if exists custom vars in database
        if (count($allVars)) {
            foreach ($allVars as $key => $var) {
                $registry = $this->loadVariable($var);
                /* @var Variable $value */
                $value = null;
                if (count($registry)) {
                    foreach ($registry as $variable) {
                        // @TODO: Priority
                        $value = $variable;
                    }
                }

                $convertedValue = null;
                if ($value) {
                    if ($useFake && !empty($value->getFake())) {
                        $convertedValue = $value->getFake();
                    } else {
                        $convertedValue = $value->getValue();
                        if ($value->getCallback()) {
                            $converter = $value->getCallback();
                            if (class_exists($converter)) {

                                // Perform cache
                                if (!array_key_exists($converter, self::$convertersCache)) {
                                    self::$convertersCache[$converter] = new $converter;
                                    if (is_callable([self::$convertersCache[$converter], 'setOm'])) {
                                        self::$convertersCache[$converter]->setOm($this->om);
                                    }
                                }
                                $converter = self::$convertersCache[$converter];

                                if ($converter instanceof VariableConversorInterface) {
                                    $entityToConverter = $converter::getClass();
                                    if ($entityToConverter && count($converters)) {
                                        foreach ($converters as $dataToConverter) {
                                            if (is_a($dataToConverter, $entityToConverter)) {
                                                // Class can converter variable
                                                $converter->setData($dataToConverter);
                                            }
                                        }
                                    } else {
                                        // @TODO: Auto search for converter?
                                    }
                                    $converteVars = $converter->getVariables();
                                    if (isset($converteVars[$var])) {
                                        $method = $converteVars[$var];
                                        $convertedValue = call_user_func([$converter, $method]);
                                    }
                                }
                            }
                        }
                    }
                }
                $value = $convertedValue;

                $allVars[$key] = [
                    'name' => $var,
                    'value' => $value,
                    'registers' => $registry,
                ];
            }
        }

        /**
         * VAR AAA.BBB.CCC is converted to ['AAA']['BBB']['CCC']
         */
        $finalParseVars = [];

        // Proccess custom and internal stored vars
        if (count($allVars)) {
            foreach ($allVars as $var) {
                if ($var['value']) {
                    $this->mountVarLevel($var['name'], $var['value'], $finalParseVars);
                }
            }
        }

        if ($this->security && $this->security->getUser()) {
            $finalParseVars['_user'] = $this->security->getUser();
        }

        // Proccess custom vars
        if (count($dataToParse)) {
            // This replace $allVars keys if exists
            foreach ($dataToParse as $key => $value) {
                $this->mountVarLevel($key, $value, $finalParseVars);
            }
        }

        if (!empty($subject)) {
            try {
                $subject = $twig->render('subject', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
                throw $e;
            }
        }

        if (!empty($document)) {
            try {
                $document = $twig->render('document', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
                throw $e;
            }
        }

        if (!empty($puretext)) {
            try {
                $puretext = $twig->render('puretext', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForEmail)) {
            try {
                $templateForEmail = $twig->render('templateForEmail', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForPdf)) {
            try {
                $templateForPdf = $twig->render('templateForPdf', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForExcel)) {
            try {
                $templateForExcel = $twig->render('templateForExcel', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForCsv)) {
            try {
                $templateForCsv = $twig->render('templateForCsv', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForTxt)) {
            try {
                $templateForTxt = $twig->render('templateForTxt', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }

        if (!empty($templateForPrinter)) {
            try {
                $templateForPrinter = $twig->render('templateForPrinter', $finalParseVars);
            } catch (\Exception | \Twig\Error\SyntaxError $e) {
                // nothing to do
            }
        }


        $template->setSubject($subject);
        $template->setTemplate($document);
        $template->setPureText($puretext);

        $template->setEmail($templateForEmail);
        $template->setPdf($templateForPdf);
        $template->setExcel($templateForExcel);
        $template->setCsv($templateForCsv);
        $template->setTxt($templateForTxt);
        $template->setPrinter($templateForPrinter);

        #dd($template);

        return $template;

    }

    /**
     * Extract all {{ XXX }} vars
     * @param $document
     * @return array
     */
    static function extractTemplateVars($document)
    {
        if (empty($document)) return [];
        preg_match_all('#{{\s*(.*?)\s*}}#i', $document, $result);
        return isset($result[1]) && is_array(@$result[1]) ? @$result[1] : [];
    }

    /**
     * @param $name
     * @return array|Variable|null
     */
    protected function loadVariable($name)
    {
        return $this->om->getRepository(Variable::class)->findBy(['name' => $name], ['id' => 'ASC']);
    }

    protected function mountVarLevel($name, $value, &$resultset)
    {
        // mount matriz
        if (is_array($value)) {
            if (!isset($resultset[$name])) {
                $resultset[$name] = [];
            }
            $resultset[$name] = array_merge_recursive($resultset[$name], $value);
        } else {
            $levels = explode('.', $name);
            $point = &$resultset;
            foreach ($levels as $level) {
                if (!isset($point[$level])) {
                    if (!is_array($point)) {
                        $point = [];
                    }
                    $point[$level] = null;
                }
                $point = &$point[$level];
            }
            return $point = $value;
        }
    }
}