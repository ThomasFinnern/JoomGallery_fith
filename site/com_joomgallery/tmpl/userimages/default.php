<?php
/**
 ******************************************************************************************
 **   @package    com_joomgallery                                                        **
 **   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
 **   @copyright  2008 - 2025  JoomGallery::ProjectTeam                                  **
 **   @license    GNU General Public License version 3 or later                          **
 *****************************************************************************************/

// No direct access
defined('_JEXEC') or die;

use Joomgallery\Component\Joomgallery\Administrator\Helper\JoomHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

// Import CSS & JS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_joomgallery.list')
  ->useStyle('com_joomgallery.site')
  ->useScript('com_joomgallery.list-view')
  ->useScript('multiselect');

$isHasAccess = $this->isUserLoggedIn && $this->isUserHasCategory && $this->isUserCoreManager;

// Access check
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $this->getAcl()->checkACL('editstate', 'com_joomgallery.image', 0, 1, true);
$saveOrder = ($listOrder == 'a.ordering' && strtolower($listDirn) == 'asc');

$config    = $this->params['configs'];
$menuParam = $this->params['menu'];

$isShowTitle = $menuParam->get('showTitle');

if ($saveOrder && !empty($this->items))
{
  $saveOrderingUrl = 'index.php?option=com_joomgallery&task=userimages.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
  HTMLHelper::_('draggablelist.draggable');
}

$panelView       = Route::_('index.php?option=com_joomgallery&view=userpanel');
$uploadView      = Route::_('index.php?option=com_joomgallery&view=userupload');
$imagesView      = Route::_('index.php?option=com_joomgallery&view=userimages');
$categoriesView  = Route::_('index.php?option=com_joomgallery&view=usercategories');

// return to userImages;
$returnURL = base64_encode('index.php?option=com_joomgallery&view=userimages');
$newCategoryView = Route::_('index.php?option=com_joomgallery&view=usercategory&layout=editCat&return=' . $returnURL);

$baseLink_ImageEdit   = 'index.php?option=com_joomgallery&view=userimage&layout=editImg&id=';
//$baseLink_ImagesFilter = 'index.php?option=com_joomgallery&view=images&filter_image=';

$canDelete = false;
/**
<?php if ($this->params['menu']->get('show_page_heading')) : ?>
<div class="page-header page-title">
<h1> <?php echo $this->escape($this->params['menu']->get('page_heading')); ?> </h1>
</div>
<?php endif; ?>
 **/
?>


<form class="jg-images"
      action="<?php echo Route::_('index.php?option=com_joomgallery&view=userimages'); ?>"
      method="post" name="adminForm" id="adminForm">

  <?php if ($isShowTitle): ?>
    <h3><?php echo Text::_('COM_JOOMGALLERY_USER_IMAGES'); ?></h3>
    <hr>
  <?php endif; ?>

  <?php if (empty($isHasAccess)): ?>
    <div>
      <?php // ToDo: discuss link to 'goto login' ?>
      <?php if (!$this->isUserLoggedIn): ?>
        <p>
          <div class="mb-2">
            <div class="alert alert-warning" role="alert">
              <span class="icon-key"></span>
              <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_PLEASE_LOGIN'); ?>
            </div>
          </div>
        </p>

      <?php else: ?>

        <?php if (!$this->isUserHasCategory): ?>
          <p>
            <div class="alert alert-warning" role="alert">
              <span class="icon-images"></span>
              <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_MISSING_CATEGORY'); ?>
              <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD_CHECK_W_ADMIN'); ?>
            </div>
          </p>
        <?php endif; ?>
        <?php if (!$this->isUserCoreManager): ?>
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

      <a class="btn btn-info" href="<?php echo $categoriesView; ?>" role="button">
        <span class="icon-images"></span>
        <?php echo Text::_('COM_JOOMGALLERY_USER_CATEGORIES'); ?>
      </a>

      <a class="btn btn-primary" href="<?php echo $uploadView; ?>" role="button">
        <span class="icon-upload"></span>
        <?php echo Text::_('COM_JOOMGALLERY_USER_UPLOAD'); ?>
      </a>

      <a class="btn btn-primary" href="<?php echo $panelView; ?>" role="button">
        <span class="icon-home"></span>
        <?php echo Text::_('COM_JOOMGALLERY_USERPANEL'); ?>
      </a>

    </div>

    <div class="row">
      <div class="col-md-12">

        <?php if (!empty($this->filterForm))          {
          $test = "test";
          echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        } ?>


        <?php if (empty($this->items)) : ?>
          <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span><span
              class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
          </div>
        <?php else : ?>

          <div class="clearfix"></div>

          <div class="table-responsive">
            <table class="table table-striped itemList" id="imageList">
              <caption class="visually-hidden">
                <?php echo Text::_('COM_JOOMGALLERY_IMAGES_TABLE_CAPTION'); ?>,
                <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
              </caption>
              <thead>
              <tr>
                <?php if ($canOrder && $saveOrder && isset($this->items[0]->ordering)): ?>
                  <th scope="col" class="w-1 text-center d-none d-md-table-cell">
                    <?php echo HTMLHelper::_('grid.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                  </th>
                <?php else : ?>
                  <th scope="col" class="w-1 d-md-table-cell"></th>
                <?php endif; ?>

                <th></th>

                <th scope="col" style="min-width:180px">
                  <?php echo HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                </th>

                <th scope="col" class="w-3 d-none d-lg-table-cell text-center">
                  <?php echo HTMLHelper::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
                </th>

                <th scope="col" class="w-3 d-none d-lg-table-cell text-center">
                  <?php echo HTMLHelper::_('grid.sort', 'COM_JOOMGALLERY_DOWNLOADS', 'a.downloads', $listDirn, $listOrder); ?>
                </th>

                <th scope="col" class="w-3 d-none d-lg-table-cell text-center">
                  <?php echo HTMLHelper::_('grid.sort', 'JCATEGORY', 'a.catid', $listDirn, $listOrder); ?>
                </th>

                <th scope="col" class="w-3 d-none d-lg-table-cell text-center">
                  <?php echo Text::_('COM_JOOMGALLERY_ACTIONS'); ?>
                </th>

                <th scope="col" class="w-3 d-none d-lg-table-cell text-center">
                  <?php echo HTMLHelper::_('grid.sort', 'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
                </th>
              </tr>
              </thead>
              <tfoot>
              <tr>
                <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                  <?php echo $this->pagination->getListFooter(); ?>
                </td>
              </tr>
              </tfoot>
              <tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" <?php endif; ?>>
              <?php foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'a.ordering');
                $canEdit = $this->getAcl()->checkACL('edit', 'com_joomgallery.image', $item->id, $item->catid, true);
                $canDelete = $this->getAcl()->checkACL('delete', 'com_joomgallery.image', $item->id, $item->catid, true);
                $canChange = $this->getAcl()->checkACL('editstate', 'com_joomgallery.image', $item->id, $item->catid, true);
                $canCheckin = $canChange || $item->checked_out == Factory::getUser()->id;
                $disabled = ($item->checked_out > 0) ? 'disabled' : '';
                ?>

                <tr class="row<?php echo $i % 2; ?>">

                  <?php if (isset($this->items[0]->ordering)) : ?>
                    <td class="text-center d-none d-md-table-cell sort-cell">
                      <?php
                      $iconClass = '';
                      if (!$canChange)
                      {
                        $iconClass = ' inactive';
                      }
                      elseif (!$saveOrder)
                      {
                        $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
                      }
                      ?>
                      <?php if ($canChange && $saveOrder) : ?>
                        <span class="sortable-handler<?php echo $iconClass ?>">
                          <span class="icon-ellipsis-v" aria-hidden="true"></span>
                        </span>
                        <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>"
                               class="width-20 text-area-order hidden">
                      <?php endif; ?>

                      <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
                    </td>
                  <?php endif; ?>

                  <td class="small d-none d-md-table-cell">
                    <!-- ToDo: fetch image anyhow as owner may see the imge -->
                    <?php if ( $item->published): ?>
                      <img class="jg_minithumb" src="<?php echo JoomHelper::getImg($item, 'thumbnail'); ?>"
                           alt="<?php echo Text::_('COM_JOOMGALLERY_THUMBNAIL'); ?>">
                    <?php else : ?>
                      <span class="icon-unpublish" aria-hidden="true"></span>
                      <span class="icon-unpublish" aria-hidden="true"></span>
                      <span class="icon-unpublish" aria-hidden="true"></span>
                      <i class="icon-<?php echo (int) $item->published ? 'check' : 'cancel'; ?>"></i>
                    <?php endif; ?>
                  </td>

                  <td scope="row" class="has-context title-cell">
                    <?php if ($canCheckin && $item->checked_out > 0) : ?>
                      <button class="js-grid-item-action tbody-icon" data-item-id="cb<?php echo $i; ?>"
                              data-item-task="userimage.checkin">
                        <span class="icon-checkedout" aria-hidden="true"></span>
                      </button>
                    <?php endif; ?>
<!--                      <a href="--><?php //echo Route::_(JoomHelper::getViewRoute('image', (int) $item->id, (int) $item->catid)); ?><!--">-->
<!--                        --><?php //echo $this->escape($item->title); ?><!-- (--><?php //echo $this->escape($item->id); ?><!--)</a>-->
<!--                      </a>-->
                    <?php
                      $route = Route::_($baseLink_ImageEdit . (int) $item->id);
                    ?>
                    <a href="<?php echo $route; ?>">
                      <?php echo $this->escape($item->title); ?>
                      <?php
                      if ($this->isDevelopSite)
                      {
                        echo '&nbsp;(' . $this->escape($item->id) . ')';
                      }
                      ?>
                    </a>
                  </td>

                  <td class="d-none d-lg-table-cell text-center">
                    <span class="badge bg-info">
                      <?php echo (int) $item->hits; ?>
                    </span>
                  </td>

                  <td class="d-none d-lg-table-cell text-center">
                    <span class="badge bg-info">
                      <?php echo (int) $item->downloads; ?>
                    </span>
                  </td>

                  <td class="d-none d-lg-table-cell text-center">
                    <?php echo $this->escape($item->cattitle); ?>
                  </td>

                  <td class="d-none d-lg-table-cell text-center">
                    <?php if ($canEdit || $canDelete): ?>
                      <?php if ($canEdit): ?>
                        <!--button class="js-grid-item-action tbody-icon <?php echo $disabled; ?>"
                            data-item-id="cb<?php echo $i; ?>"
								            data-item-task="userimage.edit" <?php echo $disabled; ?>>
                          <span class="icon-edit" aria-hidden="true"></span>
                        </button-->
                        <?php
                        // ToDo: canEdit
                        $itemId = $item->id;
                        $title = $this->escape($item->title);
                        $baseRoute = $baseLink_ImageEdit . (int) $item->id;
                        $route = Route::_($baseLink_ImageEdit . (int) $item->id);
                        $test = $route;
                        ?>
                        <a href="<?php echo $route; ?>">
                          <span class="icon-edit" aria-hidden="true"></span>
                        </a>
                      <?php endif; ?>

                      <?php if ($canDelete): ?>
                        <button class="js-grid-item-delete tbody-icon <?php echo $disabled; ?>"
                                data-item-confirm="<?php echo Text::_('JGLOBAL_CONFIRM_DELETE'); ?>"
                                data-item-id="cb<?php echo $i; ?>"
                                data-item-task="Userimage.remove" <?php echo $disabled; ?>>
                          <span class="icon-trash" aria-hidden="true"></span>
                        </button>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>

                  <td class="d-none d-lg-table-cell text-center">
                    <?php if ($canChange): ?>
                      <?php $statetask = ((int) $item->published) ? 'unpublish' : 'publish'; ?>
                      <button class="js-grid-item-action tbody-icon <?php echo $disabled; ?>"
                              data-item-id="cb<?php echo $i; ?>"
                              data-item-task="Userimage.<?php echo $statetask; ?>" <?php echo $disabled; ?>>
                        <span class="icon-<?php echo (int) $item->published ? 'check' : 'cancel'; ?>"
                              aria-hidden="true"></span>
                      </button>
                    <?php else : ?>
                      <i class="icon-<?php echo (int) $item->published ? 'check' : 'cancel'; ?>"></i>
                    <?php endif; ?>
                  </td>

                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>
  <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="return" value="<?php echo $returnURL; ?>"/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="form_submited" value="1"/>
    <input type="hidden" name="filter_order" value=""/>
    <input type="hidden" name="filter_order_Dir" value=""/>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
// ToDo: moveto scripts wa-> ...

if ($canDelete)
{
  $wa->addInlineScript("
			jQuery(document).ready(function () {
				jQuery('.delete-button').click(deleteItem);
			});

			function deleteItem() {

				if (!confirm(\"" . Text::_('COM_JOOMGALLERY_DELETE_MESSAGE') . "\")) {
					return false;
				}
			}
		", [], [], ["jquery"]);
}
?>
