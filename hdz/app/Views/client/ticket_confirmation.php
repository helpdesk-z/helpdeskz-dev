<?php
/**
 * @var $this \CodeIgniter\View\View
 */
$this->extend('client/template');
$this->section('window_title');
echo lang('Client.submitTicket.menu');
$this->endSection();
$this->section('content');
?>
    <div class="container mt-5">
        <h1 class="heading mb-5">
            <?php echo lang('Client.submitTicket.requestReceived');?>
        </h1>
        <div class="mb-3">
            <?php echo lang('Client.submitTicket.requestReceivedDescription');?>
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th colspan="2"><?php echo $ticket->subject;?></th>
            </tr>
            </thead>
            <tr>
                <td width="140"><?php echo lang('Client.form.ticketID');?>: </td>
                <td><?php echo $ticket->id;?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.fullName');?>:</td>
                <td><?php echo $ticket->fullname;?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.email');?>:</td>
                <td><?php echo $ticket->email;?></td>
            </tr>
            <tr>
                <td><?php echo lang('Client.form.department');?>:</td>
                <td><?php echo $ticket->department_name;?></td>
            </tr>
        </table>
    </div>
<?php
$this->endSection();