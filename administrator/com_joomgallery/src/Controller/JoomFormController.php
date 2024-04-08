<?php
/**
******************************************************************************************
**   @version    4.0.0-dev                                                                  **
**   @package    com_joomgallery                                                        **
**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
**   @copyright  2008 - 2023  JoomGallery::ProjectTeam                                  **
**   @license    GNU General Public License version 3 or later                          **
*****************************************************************************************/

namespace Joomgallery\Component\Joomgallery\Administrator\Controller;

// No direct access
\defined('_JEXEC') or die;

use \Joomla\Input\Input;
use \Joomla\CMS\Application\CMSApplication;
use \Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use \Joomla\CMS\MVC\Controller\FormController as BaseFormController;
use \Joomgallery\Component\Joomgallery\Administrator\Service\Access\AccessInterface;

/**
 * JoomGallery Base of Joomla Form Controller
 * 
 * Controller (controllers are where you put all the actual code) Provides basic
 * functionality, such as rendering views (aka displaying templates).
 *
 * @package JoomGallery
 * @since   4.0.0
 */
class JoomFormController extends BaseFormController
{
  /**
   * Joomgallery\Component\Joomgallery\Administrator\Extension\JoomgalleryComponent
   *
   * @access  protected
   * @var     object
   */
  protected $component;

  /**
   * JoomGallery access service
   *
   * @access  protected
   * @var     Joomgallery\Component\Joomgallery\Administrator\Service\Access\AccessInterface
   */
  protected $acl = null;

  /**
   * Constructor.
   *
   * @param   array                 $config       An optional associative array of configuration settings.
   *                                              Recognized key values include 'name', 'default_task', 'model_path', and
   *                                              'view_path' (this list is not meant to be comprehensive).
   * @param   MVCFactoryInterface   $factory      The factory.
   * @param   CMSApplication        $app          The Application for the dispatcher
   * @param   Input                 $input        Input
   * @param   FormFactoryInterface  $formFactory  The form factory.
   *
   * @since   3.0
   */
  public function __construct($config = [], MVCFactoryInterface $factory = null, ?CMSApplication $app = null, ?Input $input = null, FormFactoryInterface $formFactory = null)
  {
    parent::__construct($config, $factory, $app, $input, $formFactory);

    $this->component = $this->app->bootComponent(_JOOM_OPTION);
  }

  /**
	 * Method to get the access service class.
	 *
	 * @return  AccessInterface   Object on success, false on failure.
   * @since   4.0.0
	 */
	public function getAcl(): AccessInterface
	{
    // Create access service
    if(\is_null($this->acl))
    {
      $this->component->createAccess();
      $this->acl = $this->component->getAccess();
    }

		return $this->acl;
	}

  /**
   * Execute a task by triggering a Method in the derived class.
   *
   * @param   string  $task    The task to perform. If no matching task is found, the '__default' task is executed, if
   *                           defined.
   *
   * @return  mixed   The value returned by the called Method.
   *
   * @throws  Exception
   * @since   4.2.0
   */
  public function execute($task)
  {
    // Before execution of the task
    if(!empty($task))
    {
      $this->component->msgUserStateKey = 'com_joomgallery.'.$task.'.messages';
    }

    if(!$this->component->isRawTask($this->context))
    {
      // Get messages from session
      $this->component->msgFromSession();
    }
    

    // execute the task
    $res = parent::execute($task);

    // After execution of the task
    if(!$this->component->isRawTask($this->context))
    {
      // Print messages from session
      if(!$this->component->msgWithhold && $res->component->error)
      {
        $this->component->printError();
      }
      elseif(!$this->component->msgWithhold)
      {
        $this->component->printWarning();
        $this->component->printDebug();
      }
    }

    return $res;
  }

  /**
   * Method to check if you can add a new record.   *
   * Extended classes can override this if necessary.
   *
   * @param   array  $data  An array of input data.
   *
   * @return  boolean
   *
   * @since   1.6
   */
  protected function allowAdd($data = [])
  {
    return $this->getAcl()->checkACL('core.create', $this->option);
  }

  /**
   * Method to check if you can edit an existing record.   *
   * Extended classes can override this if necessary.
   *
   * @param   array   $data  An array of input data.
   * @param   string  $key   The name of the key for the primary key; default is id.
   *
   * @return  boolean
   *
   * @since   1.6
   */
  protected function allowEdit($data = [], $key = 'id')
  {
    return $this->getAcl()->checkACL('core.edit', $this->option);
  }
}
