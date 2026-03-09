<?php

namespace App\Console\Commands;

use App\Models\AIKnowledgeBase;
use Illuminate\Console\Command;

class GenerateKnowledgeBaseEmbeddings extends Command
{
    protected $signature = 'ai:generate-embeddings {--force : Regenerate embeddings even if they exist}';
    protected $description = 'Generate embeddings for all knowledge base entries';

    public function handle()
    {
        $this->info('Generating embeddings for knowledge base entries...');
        
        $query = AIKnowledgeBase::query();
        
        if (!$this->option('force')) {
            $query->whereNull('chunk_embeddings');
        }
        
        $entries = $query->get();
        
        if ($entries->isEmpty()) {
            $this->info('No entries need embedding generation.');
            return 0;
        }
        
        $this->info("Processing {$entries->count()} entries...");
        
        $bar = $this->output->createProgressBar($entries->count());
        $bar->start();
        
        foreach ($entries as $entry) {
            try {
                $entry->processAndStoreChunks(2000, 400);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nFailed to process entry #{$entry->id}: {$e->getMessage()}");
            }
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✓ Embeddings generated successfully!');
        
        return 0;
    }
}
