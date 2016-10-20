<ol class="breadcrumb">
    <li class="active">Home</li>
</ol>

<section id="mainWrapper">
    <div class="col-sm-offset-1 col-sm-10">
        <form action="/home">
            <p class="pull-right"><?= $sSelectProject; ?></p>
        </form>
        <p><a href="/mail" class="btn btn-success"><span class="fa fa-plus"></span> Create a new mail</a></p>

        <?php if (count($oMails) > 0): ?>
            <table class="table table-striped table-condensed table-hover table-bordered">
                <tr>
                    <th>Subject</th>
                    <th>Project</th>
                    <th>Nb. KPI</th>
                    <th>Time to generate</th>
                    <th>Last send</th>
                    <th>Last update</th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($oMails as $oMail): ?>
                    <tr>
                        <td><?= $oMail->SUBJECT; ?></td>
                        <td><?= (isset($aProjects[$oMail->PROJECT_ID])) ? $aProjects[$oMail->PROJECT_ID] : '---'; ?></td>
                        <td><?= $oMail->NB_KPI; ?></td>
                        <td><?php if ($oMail->GENERATION_DURATION > 0): ?><?= $oMail->GENERATION_DURATION; ?>s<?php endif; ?></td>
                        <td><?= $oMail->TS_SEND; ?></td>
                        <td><?= $oMail->TS_UPDATE; ?></td>
                        <td>
                            <a href="/mail/index/<?= $oMail->MAIL_ID; ?>" class="btn btn-xs btn-primary" title="Modify"><span
                                    class="fa fa-edit"></span></a>
                            <a href="/mail/send/<?= $oMail->MAIL_ID; ?>" class="btn btn-xs btn-primary sendMail"><span
                                    class="fa fa-send"></span></a>
                            <a href="/mail/delete/<?= $oMail->MAIL_ID; ?>" class="btn btn-xs btn-danger confirm"
                               title="Delete"><span class="fa fa-trash"></span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">No mails available</div>
        <?php endif; ?>
    </div>
</section>