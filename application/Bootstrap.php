<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public function _initTranslate() {
		$locale = "en";

		$translate = new Zend_Translate(array(
                'adapter' => 'ini',
                'disableNotices' => true,
		)
		);

		/*
		 $translate = new Zend_Translate(array(
		 'adapter' => 'gettext',
		 'content' => APPLICATION_PATH . '/languages/en/en-US.mo',
		 'locale'  => 'en'
		 ));
		 */

		$translate->getAdapter()->addTranslation(
		array(
                'content' => APPLICATION_PATH . '/languages/en/en.ini',
                'locale' => 'en'
                )
                );
                $translate->getAdapter()->addTranslation(
                array(
                'content' => APPLICATION_PATH . '/languages/it/it.ini',
                'locale' => 'it'
                )
                );
                
                // To change
                $translate->setLocale('en');
                
                Zend_Registry::set('Zend_Locale', $locale);
                Zend_Registry::set('Zend_Translate', $translate);
                
                

	}


	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');


	}

}

