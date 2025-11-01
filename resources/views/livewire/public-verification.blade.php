<div class="max-w-xl mx-auto py-8">
	<div class="bg-white shadow rounded p-6">
		<h2 class="text-xl font-semibold mb-4">Membership Verification</h2>
		<form wire:submit.prevent="verify">
			{{ $this->form }}
			<div class="mt-4">
				<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Verify</button>
			</div>
		</form>

		@if ($resultMessage)
			<div class="mt-6">
				@if (str_starts_with($resultMessage, 'Verified'))
					<div class="p-3 rounded bg-green-50 text-green-700">
						{{ $resultMessage }}
						@if ($validUntil)
							<span class="block text-sm">Card valid until: {{ $validUntil }}</span>
						@endif
					</div>
				@else
					<div class="p-3 rounded bg-red-50 text-red-700">{{ $resultMessage }}</div>
				@endif
			</div>
		@endif
	</div>
</div>



