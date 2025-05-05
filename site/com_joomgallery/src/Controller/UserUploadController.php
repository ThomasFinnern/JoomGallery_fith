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



} // class
