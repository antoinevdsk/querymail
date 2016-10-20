<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Mail</li>
</ol>

<section id="mainWrapper">
    <form class="form-horizontal" role="form" method="POST" action="/mail/save/<?= $oMail->MAIL_ID; ?>">
        <div class="form-group">
            <label for="name" class="col-sm-1 control-label">Subject</label>
            <div class="col-sm-10">
                <input name="SUBJECT" class="form-control" placeHolder="Subject" value="<?= $oMail->SUBJECT; ?>">
            </div>
        </div>

        <div class="row form-group">
            <label for="name" class="col-md-1 control-label">Project</label>
            <div class="col-md-4">
                <?= $sSelectProject; ?>
            </div>

            <label for="name" class="col-md-1 control-label">Template</label>
            <div class="col-md-5">
                <?= $sTemplateSelect; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-1 control-label">From</label>
            <div class="col-sm-10">
                <input name="FROM" class="form-control" placeHolder="From (email address)" value="<?= $oMail->FROM; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-1 control-label">To</label>
            <div class="col-sm-10">
                <input name="TO" class="form-control" placeHolder="Email addresses (separated by comma)"
                       value="<?= $oMail->TO; ?>">
            </div>
        </div>

        <?php if ($oMail->MAIL_ID > 0): ?>
            <div class="form-group">
                <label for="name" class="col-sm-1 control-label">WS url</label>
                <div class="col-sm-10">
                    <pre>curl <?= $sSendUrl; ?></pre>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-sm-offset-1 col-sm-10" style="padding-left:0; padding-right:0;">
            <div class="panel panel-default">
                <div class="panel-heading">Associated KPI</div>
                <div class="panel-body">
                    <?php if ($oMail->MAIL_ID > 0): ?>
                        <p><a href="/kpi/index/<?= $oMail->MAIL_ID; ?>" class="btn btn-success"><span
                                    class="fa fa-plus"></span> Create a new kpi</a></p>
                        <?php if (count($aKpiDatas) > 0): ?>
                            <table class="table table-striped table-condensed table-hover table-bordered">
                                <tr>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Mode</th>
                                    <th>Priority</th>
                                    <th>Last update</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?php foreach ($aKpiDatas as $oMailKpi): ?>
                                    <tr>
                                        <td><?= ($oMailKpi->IS_ACTIVE == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>'; ?></td>
                                        <td><?= $oMailKpi->NAME; ?></td>
                                        <td>
                                            <?= $oMailKpi->MODE; ?>
                                            <? if ($oMailKpi->MODE != MODE_HTML) echo '(' . $oMailKpi->GRAPH_MODE . ')'; ?>
                                        </td>
                                        <td><?= $oMailKpi->PRIORITY; ?></td>
                                        <td><?= $oMailKpi->TS_UPDATE; ?></td>
                                        <td>
                                            <a href="/kpi/index/<?= $oMail->MAIL_ID; ?>/<?= $oMailKpi->KPI_ID; ?>"
                                               class="btn btn-xs btn-primary" title="Edit"><span
                                                    class="fa fa-edit"></span></a>
                                            <a href="/kpi/delete/<?= $oMail->MAIL_ID; ?>/<?= $oMailKpi->KPI_ID; ?>"
                                               class="btn btn-xs btn-danger confirm" title="Delete"><span
                                                    class="fa fa-trash"></span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning" role="alert">No KPI available for this email</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">KPI are only available if the email is saved</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-10">
                <button type="submit" class="btn btn-primary"><span
                        class="fa fa-save"></span> <?php if ($oMail->MAIL_ID > 0): ?>Update<?php else: ?>Save<?php endif; ?>
                </button>
                <?php if ($oMail->MAIL_ID > 0): ?>
                    <a href="/mail/preview/<?= $oMail->MAIL_ID; ?>" target="_blank" class="btn btn-warning"><span
                            class="fa fa-search"></span> Preview</a>
                    <a href="/mail/send/<?= $oMail->MAIL_ID; ?>" class="btn btn-primary sendMail"><span
                            class="fa fa-send"></span> Send now</a>
                    <a href="/mail/delete/<?= $oMail->MAIL_ID; ?>" class="btn btn-danger confirm"><span
                            class="fa fa-trash"></span> Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</section>