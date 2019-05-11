<div class="row">
    <div class="col-12">
        <ol>
            <?php if ($faces): ?>
            <?php foreach ($properties->getDominantColors() as $key => $dominantColors): ?>
                <?php foreach ($dominantColors as $key => $dominantColor): ?>
                    <?php
                        // Preparing Values
                        $rgb = $dominantColor->getColor();
                        $score = $dominantColor->getScore();
                        $pixelFraction = $dominantColor->getPixelFraction();
                    ?>
                    <li>
                        <strong>
                            <span style="color: rgb(<?php echo $rgb->getRed(); ?>, <?php echo $rgb->getGreen(); ?>, <?php echo $rgb->getBlue(); ?>);">
                                RGB:
                            </span>
                        </strong>
                        <?php echo $rgb->getRed(); ?>, <?php echo $rgb->getGreen(); ?>, <?php echo $rgb->getBlue(); ?>
                        <br>
                        <strong>Score: </strong><?php echo number_format($score * 100 , 2) ?>%
                        <br>
                        <strong>Pixel Fraction: </strong><?php echo $pixelFraction ?>
                    </li>
                <?php endforeach ?>
            <?php endforeach ?>
            <?php endif ?>
        </ol>
    </div>
</div>
