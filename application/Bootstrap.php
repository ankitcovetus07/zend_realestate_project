<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /* protected function _initFileCache() 
      {
      $fo = array('automatic_serialization' => true,'lifetime' => 86400);
      $bo = array('cache_dir' => CACHE_PATH.'\tmp');
      $cache = Zend_Cache::factory('Core', 'File', $fo, $bo);
      Zend_Registry::set('fileCache', $cache);
      } */

    protected function _initTranslate() {
        //echo ROOT_PATH; exit;
        $translate = new Zend_Translate('array', ROOT_PATH . '/languages/', null, array('scan' => Zend_Translate::LOCALE_FILENAME, 'disableNotices' => 1));
        $registry = Zend_Registry::getInstance();

        $lang = 'en_GB';
        // Register all your "approved" locales below.
        switch ($lang) {
            case "nl_NL":
                $langLocale = 'nl_NL';
                break;
            case "en_GB":
                $langLocale = 'en_GB';
                break;
            default:
                $langLocale = 'nl_NL';
        }
        $newLocale = new Zend_Locale();
        $newLocale->setLocale($langLocale);
        $registry->set('Zend_Locale', $newLocale);
        $translate->setLocale($langLocale);

        // Save the modified translate back to registry
        $registry->set('translate', $translate);
        $registry->set('Zend_Translate', $translate);
    }

    protected function _initLogmailer() {
        $logmailerConfig = $this->getOption('logmailer');

        $mailer = new Zend_Mail();
        $transport = new Zend_Mail_Transport_Smtp(
                $logmailerConfig['smtp']['server'], $logmailerConfig['smtp']
        );

        $mailer->setDefaultTransport($transport);
        Zend_Registry::set('logmailer', $mailer);
    }

}

?>
