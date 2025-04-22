<?php

defined('_JEXEC');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//$wa = $this->document->getWebAssetManager();
//$wa->

// $isHasAccess = false;
$isHasAccess = true;

$panelView = Route::_('index.php?option=com_joomgallery&view=userpanel');
$uploadView = Route::_('index.php?option=com_joomgallery&view=userupload');
$categoriesView = Route::_('index.php?option=com_joomgallery&view=usercategories');
$newCategoryView = Route::_('index.php?option=com_joomgallery&view=user-categories/edit');
?>

  <div class="jg jg-user-panel">

      <form
              action="<?php echo $uploadView; ?>"
              method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="needs-validation"
              novalidate aria-label="<?php echo Text::_('COM_JOOMGALLERY_IMAGES_UPLOAD', true); ?>" >

          <h3><?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?></h3>

	      <?php if (empty($isHasAccess)): ?>
            <div>
                <?php // ToDo: discuss link to 'goto login' ?>
                <button type="button" class="btn btn-primary jg-no-access">
                    <span class="icon-key"></span>
                    <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_PLEASE_LOGIN'); ?>
                </button>
            </div>
        <?php else: ?>

          <a class="btn btn-success" href="<?php echo $categoriesView; ?>" role="button">
            <span class="icon-images"></span>
            <?php echo Text::_('catgories/galleries'); ?>
          </a>

          <a class="btn btn-success" href="<?php echo $newCategoryView; ?>" role="button">
            <span class="icon-new-tab"></span>
            <?php echo Text::_('new category/gallery'); ?>
          </a>

          <a class="btn btn-primary" href="<?php echo $uploadView; ?>" role="button">
            <span class="icon-upload"></span>
            <?php echo Text::_('upload'); ?>
          </a>



          <input type="hidden" name="task" value="userpanel.todo"/>
	      <?php echo HTMLHelper::_('form.token'); ?>

      </form>

    <?php endif; ?>
</div>
