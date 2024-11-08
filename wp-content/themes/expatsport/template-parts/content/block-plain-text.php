<?php $content = get_sub_field('content'); ?>
<section <?php generate_section_id($args); ?> class="text-side-by-side <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="text-side-by-side">
    <?php generate_section_background_image(false, array('imgSize'=>'banner', 'containerAttributes'=>'data-st-anim="text-side-by-side"')); ?>
    <div class="container">
        <div class="row gx-5">
            <div class="col-12 col-md-12" data-st-anim="text-side-by-side" data-st-scrub="true" data-st-start="top bottom" data-st-end="top center" data-st-trigger="self" data-st-from='{"yPercent":-20, "opacity":0}'>
                <?php if($content): ?>
                    <div class="mt-3">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>  