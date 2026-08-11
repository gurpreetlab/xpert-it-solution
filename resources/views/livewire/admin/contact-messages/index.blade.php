<div class="p-6 sm:p-8 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Contact Messages</h1>
            <p class="text-sm text-zinc-500 mt-1">Manage and respond to corporate inquiries and messages from customer contact forms.</p>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        @if($messages->isEmpty())
            <div class="py-16 text-center">
                <flux:icon icon="envelope" class="size-12 text-zinc-300 dark:text-zinc-700 mx-auto mb-4" />
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No messages found</h3>
                <p class="text-sm text-zinc-500 mt-1">There are no contact inquiries in the database.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-zinc-500 font-semibold">
                            <th class="p-4">Sender</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Date</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach($messages as $msg)
                            <tr wire:key="msg-{{ $msg->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition">
                                <td class="p-4 font-medium text-zinc-900 dark:text-white">{{ $msg->name }}</td>
                                <td class="p-4 text-zinc-500 dark:text-zinc-400">{{ $msg->email }}</td>
                                <td class="p-4 text-zinc-900 dark:text-zinc-300 font-medium truncate max-w-xs">{{ $msg->subject }}</td>
                                <td class="p-4 text-zinc-400">{{ $msg->created_at->diffForHumans() }}</td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <flux:button size="sm" variant="ghost" wire:click="viewMessage({{ $msg->id }})" class="cursor-pointer">
                                            View
                                        </flux:button>
                                        <flux:button size="sm" variant="ghost" wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Are you sure you want to delete this message?" class="cursor-pointer hover:text-rose-500">
                                            Delete
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

    <!-- Message Detail Modal -->
    <flux:modal name="message-detail-modal" wire:model="showMessageModal" class="max-w-xl">
        @if($this->selectedMessage)
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $this->selectedMessage->subject }}</h2>
                    <p class="text-xs text-zinc-400 mt-1">From: <span class="font-semibold text-zinc-600 dark:text-zinc-300">{{ $this->selectedMessage->name }}</span> ({{ $this->selectedMessage->email }}) on {{ $this->selectedMessage->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 text-sm text-zinc-800 dark:text-zinc-300 leading-relaxed whitespace-pre-wrap">
                    {{ $this->selectedMessage->message }}
                </div>

                <div class="flex justify-between items-center">
                    <flux:button variant="ghost" href="mailto:{{ $this->selectedMessage->email }}" target="_blank" icon="envelope" class="cursor-pointer">
                        Reply via Email
                    </flux:button>
                    <div class="flex gap-2">
                        <flux:button variant="filled" wire:click="deleteMessage({{ $this->selectedMessage->id }})" wire:confirm="Are you sure you want to delete this message?" class="cursor-pointer bg-rose-600 hover:bg-rose-700 text-white border-0">
                            Delete Inquiry
                        </flux:button>
                        <flux:button variant="outline" wire:click="$set('showMessageModal', false)" class="cursor-pointer">
                            Close
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
