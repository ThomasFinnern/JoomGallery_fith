<?php
/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2019 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace JoomGallery\Plugin\Console\Joomconsole\Extension;

defined('_JEXEC') or die;

//use Joomgallery\Component\JoomGallery\Administrator\Clicommand\Categorieslist;
use Joomgallery\Component\Joomgallery\Administrator\CliCommand\CategoriesList;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\CMS\MVC\Factory\MVCFactoryAwareTrait;
use Joomla\CMS\Console\Loader\WritableLoaderInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\DI\Container;
use Joomla\Event\SubscriberInterface;
use Throwable;

/**
 * Adds commands to the Joomla console
 *
 * @package     Joomla.Plugin
 * @since       2.5
 */
class JoomgalleryConsole extends CMSPlugin implements SubscriberInterface
{
  use MVCFactoryAwareTrait;

  private static $commands = [
    CategoriesList::class,
  ];

  protected $autoloadLanguage = true;

  public function init()
  {
    $this->loadLanguage();
  }

  public static function getSubscribedEvents(): array
  {
    return [
      ApplicationEvents::BEFORE_EXECUTE => 'registerCLICommands',
    ];
  }
// Joomgallery\\Component\\Joomgallery\\Administrator\\
  public function registerCLICommands(ApplicationEvent $event): void
  {
    // $test = new CategoriesList ();

    foreach (self::$commands as $commandFQN) {
      try {
        if (!class_exists($commandFQN)) {
          continue;
        }

        $command = new $commandFQN();

        if (method_exists($command, 'setMVCFactory')) {
          $command->setMVCFactory($this->getMVCFactory());
        }

        $test = $this->getApplication();

        $this->getApplication()->addCommand($command);
      } catch (Throwable $e) {
        continue;
      }
    }
  }

}

