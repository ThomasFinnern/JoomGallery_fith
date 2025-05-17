<?php
namespace Joomgallery\Component\Joomgallery\Administrator\CliCommand;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Factory\MVCFactoryAwareTrait;
use Joomla\CMS\MVC\Model\DatabaseAwareTrait;
use Joomla\Filter\InputFilter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCategory extends \Joomla\Console\Command\AbstractCommand
{
  use MVCFactoryAwareTrait;
  use DatabaseAwareTrait;

  /**
   * The default command name
   *
   * @var    string
   */
  protected static $defaultName = 'joomgallery:category:add';

  /**
   * @var   SymfonyStyle
   */
  private $ioStyle;

  /**
   * @var   InputInterface
   */
  private $cliInput;

  private $title;
  private $published;
  private $created_by;
  private $created_time;
  private $modified_by;
  private $modified_time;
  private $parent_title;

  /**
   * @inheritDoc
   */
  protected function doExecute(InputInterface $input, OutputInterface $output): int
  {
    // Configure the Symfony output helper
    $this->configureSymfonyIO($input, $output);
//    $this->ioStyle->title(Text::_('COM_JOOMGALLERY_CLI_ITEMS_LIST_DESC'));
    $this->ioStyle->title('JoomGallery add category');

    // Get the categories, using the backend model
    /** @var \Joomla\CMS\MVC\Model\BaseDatabaseModel $categoriesModel */
    $categoriesModel = $this->getMVCFactory()->createModel('Categories', 'Administrator');

    //--- assign option ----------------------------

    $this->title = $input->getOption('title') ?? null;
    $this->published = $input->getOption('published') ?? null;
    $this->created_by = $input->getOption('created_by') ?? null;
    $this->created_time = $input->getOption('created_time') ?? null;
    $this->modified_by = $input->getOption('modified_by') ?? null;
    $this->modified_time = $input->getOption('modified_time') ?? null;
    $this->parent_title = $input->getOption('parent_title') ?? null;

//    yyyy
//
//            // Get filter to remove invalid characters
//        $filter = new InputFilter();
//
//        $user = [
//          'username' => $filter->clean($this->user, 'USERNAME'),
//          'password' => $this->password,
//          'name'     => $filter->clean($this->name, 'STRING'),
//          'email'    => $this->email,
//          'groups'   => $this->userGroups,
//        ];
//
//        $userObj = User::getInstance();
//        $userObj->bind($user);
//
//
//
//
//
//
//
//    if ($owner) // created_by
//    {
//      $categoriesModel->setState('filter.created_by', $owner);
//      // rename
//      // not matching option error
//      // $input->setOption('created_by', $owner);
//
//      $app = $this->getApplication();
//      $input = $app->getInput();
//      // $input->set('created_by', $owner);
//
//      //$filter = $input->get('filter');
//
////      $filter = $input->getFilter();
////
////      $filter->set('filter.created_by', $owner);
//    }
//

    $categories = $categoriesModel->getItems();

    // If no categories are found show a warning and set the exit code to 1.
    if (empty($categories))
    {
      $this->ioStyle->warning('No categories found matching your criteria');

      return 1;
    }

    // Reshape the categories into something humans can read.
    $categories = array_map(
      function (object $item): array
      {
        return [
          $item->id,
          $item->title,
          $item->published ? Text::_('JYES') : Text::_('JNO'),
          $item->created_by,
          $item->created_time,
          $item->modified_by,
          $item->modified_time,
          $item->parent_title, // JGLOBAL_ROOT
          // $item->,

        ];
      },
      $categories
    );

    // Display the categories in a table and set the exit code to 0
    $this->ioStyle->table(
      [
//        Text::_('JGLOBAL_FIELD_ID_LABEL'),
//        Text::_('JGLOBAL_TITLE'),
//        Text::_('JPUBLISHED'),
//        Text::_('JGLOBAL_FIELD_CREATED_BY_LABEL') . ' ('.  Text::_('COM_JOOMGALLERY_OWNER') . ')', // ToDo: Owner
//        Text::_('JGLOBAL_FIELD_CREATED_LABEL'),
//        Text::_('JGLOBAL_FIELD_MODIFIED_BY_LABEL'),
//        Text::_('JGLOBAL_FIELD_MODIFIED_LABEL'),
//        Text::_('JGLOBAL_LINK_PARENT_CATEGORY_LABEL'),
        // Text::_(''),

        'ID', 'Title', 'Published', 'Created by (owner)','Created','Modified by','Modified','Parent',
      ],
      $categories
    );

    return 0;
  }

  /**
   * Configure the command.
   *
   * @return  void
   */
  protected function configure(): void
  {
//    $this->setDescription(Text::_('COM_JOOMGALLERY_CLI_ITEMS_LIST_DESC'));
//    $this->setHelp(Text::_('COM_JOOMGALLERY_CLI_ITEMS_LIST_HELP'));
//
//    $this->addOption('search', 's', InputOption::VALUE_OPTIONAL, Text::_('COM_JOOMGALLERY_CLI_CONFIG_SEARCH'));

    $this->addOption('title', 't', InputOption::VALUE_REQUIRED, 'Title');
    $this->addOption('published', null, InputOption::VALUE_OPTIONAL, 'Published (yes/no)'
    $this->addOption('created_time', null, InputOption::VALUE_OPTIONAL, 'Created time'
    $this->addOption('created_by', 'c', InputOption::VALUE_REQUIRED, 'Created by (owner)'
    $this->addOption('modified_time', null, InputOption::VALUE_OPTIONAL, ''Modified time
    $this->addOption('modified_by', 'm', InputOption::VALUE_OPTIONAL, 'Modified by'
    $this->addOption('parent_title', 'p', InputOption::VALUE_OPTIONAL, 'parent title'

    $help = "<info>%command.name%</info> will add a joomgallery category
		    \nUsage: <info>php %command.full_name%</info>";

    $this->setDescription(Text::_('Add joomgallery category'));
    $this->setHelp($help);

  }

  /**
   * Configure the IO.
   *
   * @param   InputInterface   $input   The input to inject into the command.
   * @param   OutputInterface  $output  The output to inject into the command.
   *
   * @return  void
   */
  private function configureSymfonyIO(InputInterface $input, OutputInterface $output)
  {
    $this->cliInput = $input;
    $this->ioStyle  = new SymfonyStyle($input, $output);
  }

}

