<?php

namespace App\Mail;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $account;
    private $action;

    /**
     * AccountForgotPassword constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->action = action('PagesController@refreshPassword', [
            'actId' => $this->account->act_id,
            'amrCodeRefresh' => $this->account->amr_code_refresh
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.username'))
            ->subject(__('email.forgot_password_subject'))
            ->markdown('emails.accountForgotPassword')
            ->with([
                'account' => $this->account,
                'action' => $this->action
            ]);
    }
}
