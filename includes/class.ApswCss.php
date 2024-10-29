<?php
if (!defined('ABSPATH')) {
    exit();
}

class APSWCSS {

    private $optionsSerialized;

    function __construct($optionsSerialized) {
        $this->optionsSerialized = $optionsSerialized;
    }

    /**
     * load simple tabs custom css
     */
    function customCss() {
        ?>
        <style type="text/css"><?php echo $this->optionsSerialized->customCss; ?></style>
        <?php
    }

}
