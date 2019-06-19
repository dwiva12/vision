<div class="row">
    <div class="col-12">
        <ol>
            <?php foreach ($objects as $key => $object): ?>
                <?php
                    // Assiging Colours to each face
                    $objectColorR = random_int(0, 200);
                    $objectColorG = random_int(0, 200);
                    $objectColorB = random_int(0, 200);
                    $color = [$objectColorR, $objectColorG , $objectColorB];
                    // $objectVertice = $object->getBoundingBox()->getVertices();
                    // $vertices = [
                    //     'left' => $objectVertice[0]->getX(),
                    //     'top' => $objectVertice[0]->getY(),
                    //     'right' => $objectVertice[2]->getX(),
                    //     'bottom' => $objectVertice[2]->getY()
                    // ];
                    $normalizedVertices = $object->getBoundingPoly()->getNormalizedVertices();
                    $vertices = [
                        'left' => number_format($normalizedVertices[0]->getX(), 8),
                        'top' => number_format($normalizedVertices[0]->getY(), 8),
                        'right' => number_format($normalizedVertices[2]->getX(), 8),
                        'bottom' => number_format($normalizedVertices[2]->getY(), 8)
                    ];
                    $_SESSION['objects'][$imagetoken][$key] = json_encode($vertices);
                    $_SESSION['objects']['colors'][$key] = $color;

                 ?>
                <li><h6> <strong style="color: rgb(<?php echo "$objectColorR, $objectColorG, $objectColorB"; ?>);"><?php echo $object->getName() ?></strong></h6>
                Score: <strong><?php echo number_format($object->getScore() * 100 , 2) ?>%</strong><br></li>
                <div class="w3-border">
                <div class="w3-green" style="height:15px; width:<?php echo number_format($object->getScore() * 100 , 2) ?>%"></div>
                </div>
                <br>
            <?php endforeach ?>
        </ol>
    </div>
</div>
