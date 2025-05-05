<?php

defined('_JEXEC');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//$wa = $this->document->getWebAssetManager();
//$wa->

$isHasAccess = $this->isUserLoggedIn && $this->isUserHasCategory && $this->isUserCoreManager;

$config     = $this->params['configs'];
$menuParam  = $this->params['menu'];

$isShowTitle = $menuParam->get('userPanelShowTitle');

$panelView = Route::_('index.php?option=com_joomgallery&view=userpanel');
$uploadView = Route::_('index.php?option=com_joomgallery&view=userupload');
$categoriesView = Route::_('index.php?option=com_joomgallery&view=usercategories');
$newCategoryView = Route::_('index.php?option=com_joomgallery&view=user-categories/edit');

?>

  <div class="jg jg-user-panel">

      <form
              action="<?php echo $panelView; ?>"
              method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="needs-validation"
              novalidate aria-label="<?php echo Text::_('COM_JOOMGALLERY_USERPANEL', true); ?>" >

	      <?php if ($isShowTitle): ?>
              <h3><?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?></h3>
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
                    <!--<a class="btn btn-primary" href="<?php echo $uploadView; ?>" role="button">
                        <span class="icon-upload"></span>
                        <?php echo Text::_('upload'); ?>
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
                      <?php echo Text::_('*catgories/galleries'); ?>
                  </a>

                  <a class="btn btn-success" href="<?php echo $newCategoryView; ?>" role="button">
                      <span class="icon-new-tab"></span>
                      <?php echo Text::_('*new category/gallery'); ?>
                  </a>

                  <a class="btn btn-primary" href="<?php echo $uploadView; ?>" role="button">
                      <span class="icon-home"></span>
                      <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD'); ?>
                  </a>

              </div>
              <div class="form-group">

                  ??? latest category list <br>
                  ??? latest images list <br>

              </div>
	      <?php endif; ?>

          <input type="hidden" name="task" value="image.???"/>
          <input type="hidden" name="id" value="0" />

	      <?php echo HTMLHelper::_('form.token'); ?>
      </form>

</div>
