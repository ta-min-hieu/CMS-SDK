<?php

namespace backend\components\behaviors;

use common\helpers\Helpers;
use yii\behaviors\SluggableBehavior;

class UnicodeSluggableBehavior extends SluggableBehavior {
    protected function generateSlug($slugParts)
    {
    	$unicodeSlugParts = Helpers::replace_unicode_characters($slugParts);
        return parent::generateSlug($unicodeSlugParts);
    }
}

?>