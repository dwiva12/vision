<?php use Google\Cloud\Vision\V1\Likelihood;?>
<div class="row">
    <div class="col-12">
        <ol>
            <li>
                <div class="row">
                    <div class="col-6">
                        <strong>Adult</strong>
                    </div>
                    <div class="col-6">
                        <strong><?php echo Likelihood::name($safeSearch->getAdult()) ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Spoof</strong>
                    </div>
                    <div class="col-6">
                        <strong><?php echo Likelihood::name($safeSearch->getSpoof()) ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Medical</strong>
                    </div>
                    <div class="col-6">
                        <strong><?php echo Likelihood::name($safeSearch->getMedical()) ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Violence</strong>
                    </div>
                    <div class="col-6">
                        <strong><?php echo Likelihood::name($safeSearch->getViolence()) ?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <strong>Racy</strong>
                    </div>
                    <div class="col-6">
                        <strong><?php echo Likelihood::name($safeSearch->getRacy()) ?></strong>
                    </div>
                </div>

            </li>
        </ol>
    </div>
</div>
