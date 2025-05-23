<?php

/**
 * @package         Joomla.Site
 * @subpackage      com_contact
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomgallery\Component\Joomgallery\Site\View\Userpanel;

//use Joomla\CMS\Factory;
//use Joomla\CMS\Helper\TagsHelper;
//use Joomla\CMS\Language\Multilanguage;
use Joomgallery\Component\Joomgallery\Administrator\View\JoomGalleryView;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
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
class HtmlView extends JoomGalleryView
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
   * @var    bool
   * @since  4.0.0
   */
  protected $isUserLoggedIn = false;
  /**
   * @var    bool
   * @since  4.0.0
   */
  protected $isUserHasCategory = false;

  protected $isUserCoreManager = false;
  protected $userId = 0;

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
    // $app  = Factory::getApplication();

    // Get model data
    $model       = $this->getModel();

    $this->state = $model->getState();
    $this->params = $model->getParams();

    $this->items         = $model->getItems();
    $this->pagination    = $model->getPagination();
    $this->filterForm    = $model->getFilterForm();
    $this->activeFilters = $model->getActiveFilters();

    // Check for errors.
    if(\count($errors = $this->get('Errors')))
    {
      throw new GenericDataException(\implode("\n", $errors), 500);
    }

    // $config = $this->params['configs'];

    //	user must be logged in and have one 'master/base' category
    $this->isUserLoggedIn = true;
    if ($user->guest)
    {
      $this->isUserLoggedIn = false;
    }

    // at least one category is needed for upload view
    $this->isUserHasCategory = $model->getUserHasACategory($user);

    $this->userId = $user->id;

    // Get access service
    $this->component->createAccess();
    $this->acl = $this->component->getAccess();
    // $acl       = $this->component->getAccess();

    // Needed for JgcategoryField
    // $this->isUserCoreManager = $acl->checkACL('core.manage', 'com_joomgallery');
    $this->isUserCoreManager = $this->acl->checkACL('core.manage', 'com_joomgallery');

//        // Check for errors.
//        if (count($errors = $this->get('Errors'))) {
//            $app->enqueueMessage(implode("\n", $errors), 'error');
//
//            return false;
//        }
//
//        // Create a shortcut to the parameters.
//        $this->params = $this->state->params;
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

    // $this->_prepareDocument();

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
}
