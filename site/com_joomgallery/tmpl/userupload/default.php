<?php
/*****************************************************************************************
**   @package    com_joomgallery                                                        **
**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
**   @copyright  2008 - 2025  JoomGallery::ProjectTeam                                  **
**   @license    GNU General Public License version 3 or later                          **
*****************************************************************************************/

// No direct access
defined('_JEXEC');

use Joomla\CMS\Factory;use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;
use Joomgallery\Component\Joomgallery\Administrator\Field;

//$wa = $this->document->getWebAssetManager();
//$wa->

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('com_joomgallery.uppy-uploader')
    ->useScript('bootstrap.modal')
	->useScript('bootstrap.collapse')
    ->useStyle('com_joomgallery.uppy')
    ->useStyle('com_joomgallery.admin');
HTMLHelper::_('bootstrap.tooltip');

$isHasAccess = $this->isUserLoggedIn && $this->isUserHasCategory && $this->isUserCoreManager;

$panelView = Route::_('index.php?option=com_joomgallery&view=userpanel');
$uploadView = Route::_('index.php?option=com_joomgallery&view=userupload');
$categoriesView = Route::_('index.php?option=com_joomgallery&view=usercategories');
//$newCategoryView = Route::_('index.php?option=com_joomgallery&view=user-categories/edit');
//$newCategoryView = Route::_('index.php?option=com_joomgallery&view=category&layout=edit');
$newCategoryView = Route::_('index.php?option=com_joomgallery&view=usercategory&layout=edit&id=0');

$config     = $this->params['configs'];
$menuParam  = $this->params['menu'];

$isUseOrigFilename = $config->get('jg_useorigfilename');
$isUseFilenameNumber = $config->get('jg_filenamenumber');
//$isShowTitle = $this->config->get('userUploadShowTitle');
$isShowTitle = $menuParam->get('userUploadShowTitle');

$app = Factory::getApplication();

// In case of modal
$isModal = $app->input->get('layout') === 'modal';
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $app->input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';

$displayTipData = [
	'description' => Text::_('COM_JOOMGALLERY_GENERIC_UPLOAD_DATA'),
	'id'          => 'adminForm-desc',
	'small'       => true
];
$rendererTip = new FileLayout('joomgallery.tip');


// Add language strings to JavaScript
Text::script('JCLOSE');
Text::script('JAUTHOR');
Text::script('JGLOBAL_TITLE');
Text::script('JGLOBAL_DESCRIPTION');
Text::script('JGLOBAL_VALIDATION_FORM_FAILED');
Text::script('COM_JOOMGALLERY_UPLOADING');
Text::script('COM_JOOMGALLERY_SAVING');
Text::script('COM_JOOMGALLERY_WAITING');
Text::script('COM_JOOMGALLERY_DEBUG_INFORMATION');
Text::script('COM_JOOMGALLERY_FILE_TITLE_HINT');
Text::script('COM_JOOMGALLERY_FILE_DESCRIPTION_HINT');
Text::script('COM_JOOMGALLERY_FILE_AUTHOR_HINT');
Text::script('COM_JOOMGALLERY_SUCCESS_UPPY_UPLOAD');
Text::script('COM_JOOMGALLERY_ERROR_UPPY_UPLOAD');
Text::script('COM_JOOMGALLERY_ERROR_UPPY_FORM');
Text::script('COM_JOOMGALLERY_ERROR_UPPY_SAVE_RECORD');
Text::script('COM_JOOMGALLERY_ERROR_FILL_REQUIRED_FIELDS');

$wa->addInlineScript('window.uppyVars = JSON.parse(\''. json_encode($this->js_vars) . '\');', ['position' => 'before'], [], ['com_joomgallery.uppy-uploader']);


?>

<div class="jg jg-upload">
    <form
        action="<?php echo $uploadView; ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="needs-validation"
        novalidate aria-label="<?php echo Text::_('COM_JOOMGALLERY_IMAGES_UPLOAD', true); ?>" >

	    <?php if ($isShowTitle): ?>
            <h3><?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD'); ?></h3>
	    <?php endif; ?>

        <?php if (empty($isHasAccess)): ?>
            <div>
                <?php // ToDo: discuss link to 'goto login' ?>
                <?php if ( ! $this->isUserLoggedIn): ?>
                  <p>
                      <div class="alert alert-warning" role="alert">
                          <span class="icon-key"></span>
                          <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_PLEASE_LOGIN'); ?>
                      </div>
                  </p>
                <?php else: ?>
                    <!--<a class="btn btn-primary" href="<?php echo $panelView; ?>" role="button">
                        <span class="icon-home"></span>
                        <?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?>
                    </a>-->

                    <?php if ( ! $this->isUserHasCategory): ?>
                      <p>
                          <div class="alert alert-warning" role="alert">
                              <span class="icon-images"></span>
                              <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_MISSING_CATEGORY'); ?>
                              <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_CHECK_W_ADMIN'); ?>
                          </div>
                      </p>
                    <?php endif; ?>
                        <?php if ( ! $this->isUserCoreManager): ?>
                          <p>
                              <div class="alert alert-warning" role="alert">
                                  <span class="icon-lamp"></span>
                                  <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_MISSING_RIGHTS'); ?>
                                  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_CHECK_W_ADMIN'); ?>
                              </div>
                          </p>
                        <?php endif; ?>
                <?php endif; ?>
            </div>
    	<?php else: ?>
            <div class="form-group">

                <a class="btn btn-success" href="<?php echo $categoriesView; ?>" role="button">
                    <span class="icon-images"></span>
                        <?php echo Text::_('COM_JOOMGALLERY_USER_CATEGORIES'); ?>
                </a>

                <a class="btn btn-success" href="<?php echo $newCategoryView; ?>" role="button">
                    <span class="icon-new-tab"></span>
                        <?php echo Text::_('COM_JOOMGALLERY_USER_NEW_CATEGORY'); ?>
                </a>

                <a class="btn btn-primary" href="<?php echo $panelView; ?>" role="button">
                    <span class="icon-home"></span>
                        <?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?>
                </a>
            </div>
            <div class="form-group">
                <div class="row align-items-start">
                    <div class="col-md-6 mb">
                        <div class="card">
                            <div class="card-header">
                                <h2><?php echo Text::_('COM_JOOMGALLERY_IMAGE_SELECTION'); ?></h2>
                            </div>
                            <div id="drag-drop-area">
                                <div class="card-body"><?php echo Text::_('COM_JOOMGALLERY_INFO_UPLOAD_FORM_NOT_LOADED'); ?></div>
                            </div>
                            <div class="card-body">
                                <?php echo $this->form->renderField('debug'); ?>
                            </div>
                        </div>

                      <div>
                        <?php DisplaySystemSettings ($this->uploadLimit, $this->postMaxSize, $this->memoryLimit, $this->configSize, $this->maxSize); ?>
                      </div>
                    </div>

                    <div class="col card">
                        <div class="card-header">
                            <h2><?php echo Text::_('JOPTIONS'); ?></h2>
                        </div>
                        <div class="card-body">
                            <p>
    	                        <?php echo $rendererTip->render($displayTipData); ?>
                            </p>
                            <?php echo $this->form->renderField('catid'); ?>
<!--                            --><?php //echo $this->form->renderField('catid', $this->userId); ?>
                            <?php if(!$isUseOrigFilename): ?>
                                <?php echo $this->form->renderField('title'); ?>
                                <?php if($isUseFilenameNumber): ?>
                                    <?php echo $this->form->renderField('nmb_start'); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php echo $this->form->renderField('author'); ?>
                            <?php echo $this->form->renderField('published'); ?>
                            <?php echo $this->form->renderField('access'); ?>
                            <?php echo $this->form->renderField('language'); ?>
                            <fieldset class="adminform">
                                <?php echo $this->form->getLabel('description'); ?>
                                <?php echo $this->form->getInput('description'); ?>
                            </fieldset>
                            <input type="text" id="jform_id" class="hidden form-control readonly" name="jform[id]" value="" readonly/>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <input type="hidden" name="task" value="image.ajaxsave" />
        <input type="hidden" name="jform[uploader]" value="tus" />
        <input type="hidden" name="jform[multiple]" value="1" />
	    <?php if($config->get('jg_useorigfilename')): ?>
          <input type="hidden" name="jform[title]" value="title" />
	    <?php endif; ?>
        <input type="hidden" name="id" value="0" />

	    <?php echo HTMLHelper::_('form.token'); ?>

    </form>
    <div id="popup-area"></div>

</div>

<?php
/**
 * Display system settings as collapsed
 *
 * @param   string  $title     The displayed title of the content
 * @param   array   $settings  Array with hold the data
 *
 * @since 4.0.0
 */
function DisplaySystemSettings($UploadLimit, $PostMaxSize, $MemoryLimit, $configSize, $maxSize)
{
  // $title =  Text::sprintf('COM_JOOMGALLERY_UPLOAD_LIMIT_IS', $maxSize);
  $title =  Text::sprintf('COM_JOOMGALLERY_POST_MAX_SIZE_IS', $maxSize);
  $id = 127;
  $itemId = 128;

  ?>

  <div class="card">
    <div class="accordion" id="<?php echo $id; ?>">
      <div class="accordion-item">
        <h2 class="accordion-header" id="<?php echo $itemId; ?>Header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                  data-bs-target="#<?php echo $itemId; ?>" aria-expanded="false" aria-controls="<?php echo $itemId; ?>">
            <?php echo Text::_($title); ?>
          </button>
        </h2>
        <div id="<?php echo $itemId; ?>" class="accordion-collapse collapse"
             aria-labelledby="<?php echo $itemId; ?>Header" data-bs-parent="#<?php echo $id; ?>">
          <div class="accordion-body">
            <table class="table table-striped">
              <thead>
                <tr>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="d-md-table-cell">
                    <?php echo Text::sprintf('COM_JOOMGALLERY_UPLOAD_UPLOAD_LIMIT_IS', $UploadLimit); ?>
                  </td>
                  <td class="d-md-table-cell">
                    <?php // echo $value; ?>
                  </td>
                </tr>

                <tr>
                  <td class="d-md-table-cell">
                    <?php echo Text::sprintf('COM_JOOMGALLERY_UPLOAD_POST_MAX_SIZE_IS', $PostMaxSize); ?>
                  </td>
                  <td class="d-md-table-cell">
                    <?php // echo $value; ?>
                  </td>
                </tr>

                <tr>
                  <td class="d-md-table-cell">
                    <?php echo Text::sprintf('COM_JOOMGALLERY_UPLOAD_POST_MEMORY_LIMIT_IS', $MemoryLimit); ?>
                  </td>
                  <td class="d-md-table-cell">
                    <?php // echo $value; ?>
                  </td>
                </tr>

                <tr>
                  <td class="d-md-table-cell">
                    <?php echo Text::sprintf('COM_JOOMGALLERY_UPLOAD_CONFIG_LIMIT_IS', $configSize); ?>
                  </td>
                  <td class="d-md-table-cell">
                    <?php // echo $value; ?>
                  </td>
                </tr>

                <tr>
                  <td class="d-md-table-cell">
                    <?php echo Text::sprintf('COM_JOOMGALLERY_POST_MAX_SIZE_IS', $maxSize); ?>
                  </td>
                  <td class="d-md-table-cell">
                    <?php // echo $value; ?>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div><!--/accordion-collapse-->
      </div><!--/accordion-item-->
    </div><!--/accordion -->
  </div><!--/card -->

  <?php return;

}


