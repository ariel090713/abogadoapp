<div class="flex gap-3">
    <!-- Book Another Session Button (Chat/Video) -->
    <a 
        href="{{ route('consultation.book', ['lawyer' => $consultation->lawyer->lawyerProfile, 'parent' => $consultation->id]) }}"
        class="px-6 py-3 bg-primary-700 text-white rounded-lg hover:bg-[#1E40AF] transition font-medium shadow-md hover:shadow-lg"
    >
        Add Another Service
    </a>

</div>
