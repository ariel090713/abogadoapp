<?php

namespace App\Console\Commands;

use App\Models\AIKnowledgeBase;
use Illuminate\Console\Command;

class ProcessKnowledgeBaseChunks extends Command
{
    protected $signature = 'ai:process-chunks';
    protected $description = 'Process and store chunks for all knowledge base entries';

    public function handle()
    {
        $this->info('Processing knowledge base entries...');
        
        $entries = AIKnowledgeBase::all();
        
        if ($entries->isEmpty()) {
            $this->warn('No knowledge base entries found.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($entries->count());
        $bar->start();
        
        foreach ($entries as $kb) {
            $kb->processAndStoreChunks(500, 100);
            $chunkCount = is_array($kb->chunks) ? count($kb->chunks) : 0;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info('✓ Processed ' . $entries->count() . ' knowledge base entries');
        
        // Show summary
        $this->table(
            ['ID', 'Title', 'Chunks', 'Hash'],
            $entries->map(function ($kb) {
                return [
                    $kb->id,
                    substr($kb->title, 0, 40),
                    is_array($kb->chunks) ? count($kb->chunks) : 0,
                    substr($kb->content_hash ?? 'N/A', 0, 16) . '...',
                ];
            })
        );
        
        return 0;
    }
}
