<div class="row">
    <div class="col-12">
        <ol>
            <?php if ($logos): ?>
                <?php foreach ($logos as $key => $logo): ?>
                    <li>
                        <h6>
                            <?php echo ucfirst($logo->getDescription()) ?>
                        </h6>
                        Confidence: <strong><?php echo number_format($logo->getScore() * 100 , 2) ?></strong>
                        <br><br>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ol>
    </div>
</div>
