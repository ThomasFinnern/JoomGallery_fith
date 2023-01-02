<?php
/**
******************************************************************************************
**   @version    4.0.0                                                                  **
**   @package    com_joomgallery                                                        **
**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
**   @copyright  2008 - 2022  JoomGallery::ProjectTeam                                  **
**   @license    GNU General Public License version 2 or later                          **
*****************************************************************************************/

namespace Joomgallery\Component\Joomgallery\Administrator\Service\Messenger;

// No direct access
\defined('_JEXEC') or die;

use \Joomgallery\Component\Joomgallery\Administrator\Service\Messenger\MessengerInterface;
use \Joomgallery\Component\Joomgallery\Administrator\Service\Messenger\MessageInterface;
use \Joomgallery\Component\Joomgallery\Administrator\Extension\ServiceTrait;

/**
 * Messenger Class
 *
 * Provides methods to send all kind of messages in the gallery.
 *
 * @package JoomGallery
 * @since   4.0.0
 */
abstract class Messenger implements MessengerInterface
{
  use ServiceTrait;

  /**
   * Message object
   *
   * @var MessageInterface
   * 
   * @since  4.0.0
   */
  protected $message = null;
}
