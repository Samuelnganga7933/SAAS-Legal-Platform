<?php

namespace App\Livewire;

use App\Models\Case;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\Attributes\Validate;

class CalendarView extends Component
{
    #[Validate('required|string|max:255')]
    public $eventTitle = '';

    #[Validate('required|date')]
    public $eventDate = '';

    #[Validate('nullable|date_format:H:i')]
    public $eventTime = '';

    #[Validate('required|in:deadline,task,call,appointment,other')]
    public $eventType = 'other';

    #[Validate('nullable|exists:cases,id')]
    public $eventMatterId = '';

    #[Validate('nullable|string|max:1000')]
    public $eventNotes = '';

    public $currentMonth = 1;
    public $currentYear = 2026;
    public $showAddForm = false;
    public $selectedEvent = null;
    public $events = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->currentMonth = $now->month;
        $this->currentYear = $now->year;
        $this->loadEvents();
    }

    public function prevMonth()
    {
        if ($this->currentMonth === 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        } else {
            $this->currentMonth--;
        }
        $this->selectedEvent = null;
        $this->loadEvents();
    }

    public function nextMonth()
    {
        if ($this->currentMonth === 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        } else {
            $this->currentMonth++;
        }
        $this->selectedEvent = null;
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $this->events = Event::whereBetween('event_date', [$startDate, $endDate])
            ->with(['matter', 'assignedUser', 'creator'])
            ->get()
            ->groupBy(function ($event) {
                return $event->event_date->format('Y-m-d');
            })
            ->toArray();
    }

    public function selectEvent($id)
    {
        $this->selectedEvent = Event::with(['matter', 'assignedUser', 'creator'])->find($id);
    }

    public function toggleForm()
    {
        $this->showAddForm = !$this->showAddForm;
        if ($this->showAddForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['eventTitle', 'eventDate', 'eventTime', 'eventType', 'eventMatterId', 'eventNotes']);
    }

    public function saveEvent()
    {
        $this->validate();

        Event::create([
            'title' => $this->eventTitle,
            'type' => $this->eventType,
            'event_date' => $this->eventDate,
            'event_time' => $this->eventTime ?: null,
            'matter_id' => $this->eventMatterId ?: null,
            'notes' => $this->eventNotes ?: null,
            'created_by' => auth()->id(),
        ]);

        $this->showAddForm = false;
        $this->resetForm();
        $this->loadEvents();
        session()->flash('message', 'Event created successfully.');
    }

    public function deleteEvent($id)
    {
        Event::find($id)?->delete();
        $this->selectedEvent = null;
        $this->loadEvents();
        session()->flash('message', 'Event deleted successfully.');
    }

    public function editEvent($id)
    {
        $event = Event::find($id);
        if (!$event) return;

        $this->eventTitle = $event->title;
        $this->eventDate = $event->event_date->format('Y-m-d');
        $this->eventTime = $event->event_time;
        $this->eventType = $event->type;
        $this->eventMatterId = $event->matter_id;
        $this->eventNotes = $event->notes;
        $this->showAddForm = true;
        $this->selectedEvent = null;

        $event->delete();
    }

    public function render()
    {
        return view('livewire.calendar-view', [
            'matters' => Case::where('user_id', auth()->id())->get(),
            'calendarDays' => $this->getCalendarDays(),
        ]);
    }

    private function getCalendarDays()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $startDate = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
        $endDate = $lastDay->copy()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $days[] = $current->copy();
            $current->addDay();
        }

        return $days;
    }
}
