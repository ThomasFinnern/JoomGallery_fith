<?php
/**
******************************************************************************************
**   @package    com_joomgallery                                                        **
**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
**   @copyright  2008 - 2025  JoomGallery::ProjectTeam                                  **
**   @license    GNU General Public License version 3 or later                          **
*****************************************************************************************/

namespace Joomgallery\Component\Joomgallery\Site\Controller;

// No direct access
\defined('_JEXEC') or die;

use Joomgallery\Component\Joomgallery\Administrator\Controller\JoomFormController;
use Joomla\CMS\Response\JsonResponse;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

/**
 * Upload controller class.
 *
 * @package JoomGallery
 * @since   4.0.0
 */
class UserUploadController extends JoomFormController
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomgallery\Component\Joomgallery\Administrator\Controller\JoomFormController  The model.
	 *
	 * @since   1.6.4
	 */
	public function getModel($name = 'UserUpload', $prefix = 'Site', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, ['ignore_request' => false]);
	}


	/**
	 * Method to add multiple new image records.
	 *
	 * @return  boolean  True if the record can be added, false if not.
	 *
	 * @since   4.0
	 */
	public function ajaxsave() : void
	{
		$result  = array('error' => false);

		try
		{
			if(!parent::save())
			{
				$result['success'] = false;
				$result['error']   = $this->message;
			}
			else
			{
				$result['success'] = true;
				$result['record'] = $this->component->cache->get('imgObj');
			}

			$json = json_encode($result, JSON_FORCE_OBJECT);
			echo new JsonResponse($json);

			$this->app->close();
		}
		catch(\Exception $e)
		{
			echo new JsonResponse($e);

			$this->app->close();
		}

	}















}
