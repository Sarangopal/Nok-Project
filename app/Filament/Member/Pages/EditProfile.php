<?php

namespace App\Filament\Member\Pages;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.member.pages.edit-profile';

    protected static ?string $navigationLabel = 'Edit Profile';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Edit Profile';

    // Hide from left sidebar navigation while keeping the route available
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $member = Auth::guard('members')->user();

        $this->form->fill([
            'memberName' => $member->memberName,
            'email' => $member->email,
            'mobile' => $member->mobile,
            'address' => $member->address,
            'whatsapp' => $member->whatsapp,
            'phone_india' => $member->phone_india,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('memberName')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile')
                    ->label('Mobile')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('whatsapp')
                    ->label('WhatsApp')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('phone_india')
                    ->label('Phone (India)')
                    ->tel()
                    ->maxLength(20),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $member = Auth::guard('members')->user();
        $member->update($this->form->getState());

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->color('primary'),
            Action::make('cancel')
                ->label('Cancel')
                ->url(route('filament.member.pages.member-dashboard')),
        ];
    }
}
