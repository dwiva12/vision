<div class="row">
    <?php if ($web): ?>
    <div class="col-6">
        <h5>Best Guess Labels</h5>
        <hr style="border: 1px solid grey;">
        <ol>
            <?php foreach ($web->getBestGuessLabels() as $key => $label): ?>
                <li><h6><strong><?php echo ucfirst($label->getLabel()); ?></strong></h6> Language Code: <strong><?php echo $label->getLanguageCode(); ?></strong></li>
            <?php endforeach ?>
        </ol>
        <hr><hr>
        <h5>Entities</h5>
        <hr style="border: 1px solid grey;">
        <ol>
            <?php foreach ($web->getWebEntities() as $key => $entity): ?>
                <li><h6><strong><?php echo ucfirst(($entity->getDescription() != '') ? $entity->getDescription() : '<No Description>' ); ?></strong></h6> Score: <strong><?php echo number_format($entity->getScore() * 100 , 2) ?></strong></li>
            <?php endforeach ?>
        </ol>
    </div>
    <div class="col-6">
        <h5>Matched Images</h5>
        <hr style="border: 1px solid grey;">
        <ol>
        <div style="height:150px;width:250px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
            <?php foreach ($web->getFullMatchingImages() as $key => $matchImage): ?>
                <li><a href="<?php echo $matchImage->getUrl(); ?>"><?php echo $matchImage->getUrl(); ?></a></li>
            <?php endforeach ?>
        </div>
        </ol>
        <hr><hr>
        <h5>Partially Matched Images</h5>
        <hr style="border: 1px solid grey;">
        <ol>
        <div style="height:150px;width:250px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
            <?php foreach ($web->getPartialMatchingImages() as $key => $partialMatchingImage): ?>
                <li><a href="<?php echo $partialMatchingImage->getUrl(); ?>"><?php echo $partialMatchingImage->getUrl(); ?></a></li>
            <?php endforeach ?>
        </div>
        </ol>
        <hr>
        <h5>Pages</h5>
        <hr style="border: 1px solid grey;">
        <ol>
        <div style="height:150px;width:250px;border:1px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
            <?php foreach ($web->getPagesWithMatchingImages() as $key => $page): ?>
                <li><a href="<?php echo $page->getUrl(); ?>"><?php echo $page->getUrl(); ?></a></li>
            <?php endforeach ?>
        </div>
        </ol>
    </div>
    <?php endif ?>
</div>
