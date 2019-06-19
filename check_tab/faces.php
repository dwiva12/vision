<?php
use Google\Cloud\Vision\V1\FaceAnnotation\Landmark\Type;
use Google\Cloud\Vision\V1\Likelihood; ?>
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

                        $faceMark = [];
                        $normalizedVertices = $face->getBoundingPoly()->getVertices();
                        $vertices = [
                            'left' => number_format($normalizedVertices[0]->getX(), 8),
                            'top' => number_format($normalizedVertices[0]->getY(), 8),
                            'right' => number_format($normalizedVertices[2]->getX(), 8),
                            'bottom' => number_format($normalizedVertices[2]->getY(), 8)
                        ];

                        $landmarkPos = [];
                        foreach ($face->getLandmarks() as $key1 => $landmark) {
                            switch ($landmark->getType()) {
                                case TYPE::LEFT_EYE:
                                case TYPE::RIGHT_EYE:
                                case TYPE::UPPER_LIP:
                                case TYPE::LOWER_LIP:
                                $landmarkPos[$key1] = [
                                        'x' => $landmark->getPosition()->getX(),
                                        'y' => $landmark->getPosition()->getY()
                                    ];
                                    break;
                                default:
                                    // code...
                                    break;
                            }
                        }

                        $faceMark = [
                            'vertices' => $vertices,
                            'landmarks' => $landmarkPos
                        ];
                        $_SESSION['faces'][$imagetoken][$key] = json_encode($faceMark);
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
                                <strong><?php echo Likelihood::name($face->getJoyLikelihood()) ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Sedih</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo Likelihood::name($face->getSorrowLikelihood()) ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Marah</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo Likelihood::name($face->getAngerLikelihood()) ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Terkejut</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo Likelihood::name($face->getSurpriseLikelihood()) ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Gambar Kabur</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo Likelihood::name($face->getBlurredLikelihood()) ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong>Penutup Kepala</strong>
                            </div>
                            <div class="col-6">
                                <strong><?php echo Likelihood::name($face->getHeadwearLikelihood()) ?></strong>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ol>
    </div>
</div>
