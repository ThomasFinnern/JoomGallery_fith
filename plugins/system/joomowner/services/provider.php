defined('_JEXEC') || die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Console\ATS\Extension\ATS;

return new class implements ServiceProviderInterface {
  public function register(Container $container)
  {
    $container->registerServiceProvider(new MVCFactory('Acme\\Component\\Example'));

    $container->set(
      PluginInterface::class,
      function (Container $container) {
        $config     = (array) PluginHelper::getPlugin('console', 'example');
        $subject    = $container->get(DispatcherInterface::class);
        $mvcFactory = $container->get(MVCFactoryInterface::class);
        $plugin     = new Example($subject, $config)

        $plugin->setMVCFactory($mvcFactory);

        return $plugin;
      }
    );
  }
};
