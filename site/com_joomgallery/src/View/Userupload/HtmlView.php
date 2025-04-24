<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomgallery\Component\Joomgallery\Site\View\Userupload;

//use Joomla\CMS\Factory;
//use Joomla\CMS\Helper\TagsHelper;
//use Joomla\CMS\Language\Multilanguage;
use Joomgallery\Component\Joomgallery\Administrator\View\JoomGalleryView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
//use Joomla\Component\Contact\Administrator\Helper\ContactHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * HTML Contact View class for the Contact component
 *
 * @since  4.0.0
 */
class HtmlView extends JoomGalleryView // BaseHtmlView
{
    /**
     * @var    \Joomla\CMS\Form\Form
     * @since  4.0.0
     */
    protected $form;

    /**
     * @var    object
     * @since  4.0.0
     */
    protected $item;

    /**
     * @var    string
     * @since  4.0.0
     */
    protected $return_page;

    /**
     * @var    string
     * @since  4.0.0
     */
    protected $pageclass_sfx;

    /**
     * @var    \Joomla\Registry\Registry
     * @since  4.0.0
     */
    protected $state;

    /**
     * @var    \Joomla\Registry\Registry
     * @since  4.0.0
     */
    protected $params;

    /**
     * @var    \Joomla\Registry\Registry
     * @since  4.0.0
     */
    protected $config;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void|boolean
     *
     * @throws \Exception
     * @since  4.0.0
     */
    public function display($tpl = null)
    {
        $user = $this->getCurrentUser();
        $app  = Factory::getApplication();

        // Get model data.
        $this->state       = $this->get('State');
        //$this->item        = $this->get('Item');
        $this->form        = $this->get('Form');
        $this->params      = $this->get('Params');
//        $this->return_page = $this->get('ReturnPage');

	    $this->config     = $this->params['configs'];


	    // Add variables to JavaScript
	    $js_vars               = new \stdClass();
	    $js_vars->maxFileSize  = (100 * 1073741824); // 100GB
	    $js_vars->TUSlocation  = $this->getTusLocation (); // $this->item->tus_location;

	    $js_vars->allowedTypes = $this->getAllowedTypes();

	    $js_vars->uppyTarget   = '#drag-drop-area';          // Id of the DOM element to apply the uppy form
	    $js_vars->uppyLimit    = 5;                          // Number of concurrent tus upploads (only file upload)
	    $js_vars->uppyDelays   = array(0, 1000, 3000, 5000); // Delay in ms between upload retrys

	    $js_vars->semaCalls    = $this->config->get('jg_parallelprocesses', 1); // Number of concurrent async calls to save the record to DB (including image processing)
	    $js_vars->semaTokens   = 100;                                           // Prealloc space for 100 tokens

	    $this->js_vars = $js_vars;


//
//        if (empty($this->item->id)) {
//            $authorised = $user->authorise('core.create', 'com_contact') || count($user->getAuthorisedCategories('com_contact', 'core.create'));
//        } else {
//            // Since we don't track these assets at the item level, use the category id.
//            $canDo      = ContactHelper::getActions('com_contact', 'category', $this->item->catid);
//            $authorised = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by === $user->id);
//        }
//
//        if ($authorised !== true) {
//            $app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
//            $app->setHeader('status', 403, true);
//
//            return false;
//        }
//
//        $this->item->tags = new TagsHelper();
//
//        if (!empty($this->item->id)) {
//            $this->item->tags->getItemTags('com_contact.contact', $this->item->id);
//        }
//
//        // Check for errors.
//        if (count($errors = $this->get('Errors'))) {
//            $app->enqueueMessage(implode("\n", $errors), 'error');
//
//            return false;
//        }
//

//        // Escape strings for HTML output
//        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''));
//
//        // Override global params with contact specific params
//        $this->params->merge($this->item->params);
//
//        // Propose current language as default when creating new contact
//        if (empty($this->item->id) && Multilanguage::isEnabled()) {
//            $lang = $this->getLanguage()->getTag();
//            $this->form->setFieldAttribute('language', 'default', $lang);
//        }
//
//        $this->_prepareDocument();

        parent::display($tpl);
    }

//    /**
//     * Prepares the document
//     *
//     * @return  void
//     *
//     * @throws \Exception
//     *
//     * @since  4.0.0
//     */
//    protected function _prepareDocument()
//    {
//        $app = Factory::getApplication();
//
//        // Because the application sets a default page title,
//        // we need to get it from the menu item itself
//        $menu = $app->getMenu()->getActive();
//
//        if ($menu) {
//            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
//        } else {
//            $this->params->def('page_heading', Text::_('COM_CONTACT_FORM_EDIT_CONTACT'));
//        }
//
//        $title = $this->params->def('page_title', Text::_('COM_CONTACT_FORM_EDIT_CONTACT'));
//
//        $this->setDocumentTitle($title);
//
//        $pathway = $app->getPathWay();
//        $pathway->addItem($title, '');
//
//        if ($this->params->get('menu-meta_description')) {
//            $this->getDocument()->setDescription($this->params->get('menu-meta_description'));
//        }
//
//        if ($this->params->get('menu-meta_keywords')) {
//            $this->getDocument()->setMetaData('keywords', $this->params->get('menu-meta_keywords'));
//        }
//
//        if ($this->params->get('robots')) {
//            $this->getDocument()->setMetaData('robots', $this->params->get('robots'));
//        }
//    }

	/**
	 * Get array of all allowed filetypes based on the config parameter jg_imagetypes.
	 *
	 * @return  array  List with all allowed filetypes
	 *
	 */
	protected function getAllowedTypes()
	{
		$types = \explode(',', $this->config->get('jg_imagetypes'));

		// add different types of jpg files
		$jpg_array = array('jpg', 'jpeg', 'jpe', 'jfif');
		if (\in_array('jpg', $types) || \in_array('jpeg', $types) || \in_array('jpe', $types) || \in_array('jfif', $types))
		{
			foreach ($jpg_array as $jpg)
			{
				if(!\in_array($jpg, $types))
				{
					\array_push($types, $jpg);
				}
			}
		}

		// add point to types
		foreach ($types as $key => $type)
		{
			if(\substr($type, 0, 1) !== '.')
			{
				$types[$key] = '.'. \strtolower($type);
			}
			else
			{
				$types[$key] = \strtolower($type);
			}
		}

		return $types;
	}

	private function getTusLocation()
	{

		// Create tus server
		$this->component->createTusServer();
		$server = $this->component->getTusServer();

		$tus_location = $server->getLocation();

		return $tus_location;
	}


} // class
