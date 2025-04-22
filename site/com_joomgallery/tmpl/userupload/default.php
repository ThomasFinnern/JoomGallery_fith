<?php

defined('_JEXEC');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;

//$wa = $this->document->getWebAssetManager();
//$wa->

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

// $isHasAccess = false;
$isHasAccess = true;

$panelView = Route::_('index.php?option=com_joomgallery&view=userpanel');
$uploadView = Route::_('index.php?option=com_joomgallery&view=userupload');
$categoriesView = Route::_('index.php?option=com_joomgallery&view=usercategories');
$newCategoryView = Route::_('index.php?option=com_joomgallery&view=user-categories/edit');
?>

<div class="jg jg-upload">

    <form
        action="<?php echo $uploadView; ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="needs-validation"
        novalidate aria-label="<?php echo Text::_('COM_JOOMGALLERY_IMAGES_UPLOAD', true); ?>" >

        <h3><?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD'); ?></h3>

        <?php if (empty($isHasAccess)): ?>
      <div>
        <?php // ToDo: discuss link to 'goto login' ?>
          <button type="button" class="btn btn-primary jg-no-access">
            <span class="icon-key"></span>
            <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_PLEASE_LOGIN'); ?>
          </button>
          <a class="btn btn-primary" href="<?php echo $panelView; ?>" role="button">
              <span class="icon-home"></span>
              <?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?>
          </a>
      </div>
	<?php else: ?>
            <div class="form-group">

            <a class="btn btn-success" href="<?php echo $categoriesView; ?>" role="button">
                <span class="icon-images"></span>
			        <?php echo Text::_('catgories/galleries'); ?>
            </a>

            <a class="btn btn-success" href="<?php echo $newCategoryView; ?>" role="button">
                <span class="icon-new-tab"></span>
			        <?php echo Text::_('new category/gallery'); ?>
            </a>

            <a class="btn btn-primary" href="<?php echo $panelView; ?>" role="button">
                <span class="icon-home"></span>
			        <?php echo Text::_('user panel'); ?>
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
                        <hr />
                        <div class="card-body ToDo:yyyy">
                            <?php $this->form->renderField('debug'); ?>
                        </div>
                    </div>
                </div>
                <div class="col card">
                    <div class="card-header">
                        <h2><?php echo Text::_('JOPTIONS'); ?></h2>
                    </div>
                    <div class="card-body">
                        <<p>
                            <?php
                            $displayData = [
                                'description' => Text::_('COM_JOOMGALLERY_GENERIC_UPLOAD_DATA'),
                                'id'          => 'adminForm-desc',
                                'small'       => true
                            ];
                            $renderer = new FileLayout('joomgallery.tip');
                            ?>
                            <?php echo $renderer->render($displayData); ?>
                        </p>
                        <?php echo $this->form->renderField('catid'); ?>
<!--                        --><?php //if(!empty($this->config->get('jg_useorigfilename'))): ?>
<!--                            --><?php //echo $this->form->renderField('title'); ?>
<!--                            --><?php //if(empty($this->config->get('jg_filenamenumber'))): ?>
<!--                                --><?php //echo $this->form->renderField('nmb_start'); ?>
<!--                            --><?php //endif; ?>
<!--                        --><?php //endif; ?>
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

        <input type="hidden" name="task" value="upload.ajaxsave"/>

<!--	    --><?php //if($this->config->get('jg_useorigfilename')): ?>
<!--          <input type="hidden" name="jform[title]" value="title" />-->
<!--	    --><?php //endif; ?>
        <input type="hidden" name="id" value="0" />

	    <?php echo HTMLHelper::_('form.token'); ?>

    </form>
    <div id="popup-area"></div>

</div>
