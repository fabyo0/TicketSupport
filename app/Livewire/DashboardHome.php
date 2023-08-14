<?php

namespace App\Livewire;

use App\Models\Ticket;
use Auth;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DashboardHome extends Component
{
    public int $closedTickets = 0;
    public int $openTickets = 0;
    public int $totalTickets = 0;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $this->closedTickets = Ticket::closed()->count();
            $this->openTickets = Ticket::open()->count();
        } elseif ($user->hasRole('Agent')) {
            $this->closedTickets = Ticket::assignedToAgent($user)->closed()->count();
            $this->openTickets = Ticket::assignedToAgent($user)->open()->count();
        } else {
            $this->closedTickets = Ticket::byUser($user)->closed()->count();
            $this->openTickets = Ticket::byUser($user)->open()->count();
        }

        $this->totalTickets = $this->closedTickets + $this->openTickets;
    }

    #[Layout('layouts.dashboard')]
    public function render(): View
    {
        return view('livewire.dashboard-home');
    }
}
