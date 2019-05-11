<div class="row">
    <div class="col-12">
        <ol>
            <?php if ($faces): ?>
                <?php foreach ($faces as $key => $face): ?>
                    <?php
                        // Assiging Colours to each face
                        $faceColorR = random_int(0, 200);
                        $faceColorG = random_int(0, 200);
                        $faceColorB = random_int(0, 200);
                        $color = [$faceColorR, $faceColorG , $faceColorB];
                        $_SESSION['faces'][$imagetoken][$key] = json_encode($face->getLandmarks());
                        $_SESSION['faces']['colors'][$key] = $color;

                     ?>
                    <li>
                        <strong style="color: rgb(<?php echo "$faceColorR, $faceColorG, $faceColorB"; ?>);">Face <?php echo $key + 1 ?></strong>
                        <hr style="border: 1px solid grey;">
                        <div class="row">
                            <div class="col-6">
                                <strong>Gembira</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getJoyLikelihood() ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Sedih</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getSorrowLikelihood() ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Marah</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getAngerLikelihood() ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Terkejut</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getSurpriseLikelihood() ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Gambar Kabur</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getBlurredLikelihood() ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Penutup Kepala</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo $face->getHeadwearLikelihood() ?></strong>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ol>
    </div>
</div>
