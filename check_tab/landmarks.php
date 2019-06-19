<div class="row">
    <div class="col-12">
        <ol>
            <?php if ($landmarks): ?>
                <?php foreach ($landmarks as $key => $landmark): ?>
                    <li><h6><?php echo ucfirst($landmark->getDescription()) ?></h6> Confidence: <strong><?php echo number_format($landmark->getScore()*100, 2) ?></strong><br><br></li>
                <?php endforeach ?>
            <?php endif ?>
        </ol>
    </div>
</div>
