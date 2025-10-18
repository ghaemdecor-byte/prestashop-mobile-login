<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class MobileLogin extends Module
{
    public function __construct()
    {
        $this->name = 'mobilelogin';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Mobile Login');
        $this->description = $this->l('Enable login and registration with mobile number.');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->createDatabaseTables();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->dropDatabaseTables();
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path . 'assets/css/mobilelogin.css');
        $this->context->controller->addJS($this->_path . 'assets/js/mobilelogin.js');
    }

    private function createDatabaseTables()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "customer_mobile` (
            `id_customer` INT(11) NOT NULL,
            `mobile` VARCHAR(15) NOT NULL,
            PRIMARY KEY (`id_customer`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";
        return Db::getInstance()->execute($sql);
    }

    private function dropDatabaseTables()
    {
        return Db::getInstance()->execute("DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "customer_mobile`");
    }
}
