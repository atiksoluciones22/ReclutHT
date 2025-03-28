<?php

namespace App\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\VIP\DocumentManagementTalentData;
use App\Services\{DBService, FileService};

class UploadDocument extends Component
{
    use LivewireAlert;

    public $subject, $file;

    protected $DBService, $listeners = ['fileUploaded'];

    public function __construct()
    {
        $this->DBService = new DBService;
    }

    protected function rules()
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'file' => ['required']
        ];
    }

    public function submit()
    {
        try {
           $this->validate();

           $userCode = auth()->user()->COD;

           $this->DBService->insert(DocumentManagementTalentData::class,
           [
               [
                   'CANDID' => $userCode,
                   'NOM' => $this->subject,
                   'TIPO' => 'Documento web',
                   'PORFTP' => '*',
                   'RUTFTP' => (new FileService())->uploadFile($this->file),
               ]
           ], wheres: ['CANDID' => $userCode], returnData: true);

            $this->alertToast('success', 'El archivo se cargo correctamente.');

            $this->dispatch('reloadPage');
        } catch (\Throwable $th) {
            $this->alertToast('warning', 'Existen campos que son necesarios para cargar el archivo.');
            $this->validate();
        }
    }

    public function fileUploaded($file)
    {
        $this->file = $file;
    }

    private function alertToast($type, $message){
        $this->alert($type, $message, [
            'position' => 'top-end',
            'timer' => 4000,
            'toast' => true,
            'showConfirmButton' => false,
            'onConfirmed' => '',
            'text' => '',
            'showCancelButton' => false,
            'onDismissed' => '',
            'showDenyButton' => false,
            'onDenied' => '',
            'width' => '',
            'timerProgressBar' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.upload-document');
    }
}
