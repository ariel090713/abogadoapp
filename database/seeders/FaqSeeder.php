<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // General
            [
                'question' => 'What is AbogadoMo?',
                'answer' => 'AbogadoMo is an online platform that connects clients with qualified lawyers in the Philippines. We provide easy access to legal consultations, document preparation, and legal advice through our secure platform.',
                'category' => 'general',
                'order' => 1,
            ],
            [
                'question' => 'How does AbogadoMo work?',
                'answer' => 'Simply create an account, browse our network of verified lawyers, and book a consultation. You can choose between video consultations, chat consultations, or request document preparation services. Payment is secure and processed through our platform.',
                'category' => 'general',
                'order' => 2,
            ],
            [
                'question' => 'Is AbogadoMo available nationwide?',
                'answer' => 'Yes! AbogadoMo connects you with lawyers across the Philippines. Our online platform allows you to access legal services regardless of your location.',
                'category' => 'general',
                'order' => 3,
            ],

            // Consultations
            [
                'question' => 'How do I book a consultation?',
                'answer' => 'After creating an account, browse our lawyer directory, select a lawyer that matches your needs, and click "Book Consultation". Choose your preferred date and time, provide details about your legal concern, and proceed with payment.',
                'category' => 'consultations',
                'order' => 4,
            ],
            [
                'question' => 'What types of consultations are available?',
                'answer' => 'We offer video consultations for face-to-face discussions, chat consultations for quick questions, and document review services. You can also request follow-up consultations for ongoing cases.',
                'category' => 'consultations',
                'order' => 5,
            ],
            [
                'question' => 'How long does a consultation last?',
                'answer' => 'Standard consultations typically last 30-60 minutes, depending on the complexity of your case and the lawyer\'s assessment. The duration is agreed upon when booking.',
                'category' => 'consultations',
                'order' => 6,
            ],
            [
                'question' => 'Can I reschedule my consultation?',
                'answer' => 'Yes, you can request to reschedule your consultation up to 24 hours before the scheduled time. Contact your lawyer through the platform to arrange a new time.',
                'category' => 'consultations',
                'order' => 7,
            ],

            // Payments
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept major credit cards, debit cards, and online payment methods through our secure payment gateway. All transactions are encrypted and secure.',
                'category' => 'payments',
                'order' => 8,
            ],
            [
                'question' => 'When do I pay for the consultation?',
                'answer' => 'Payment is required when booking a consultation. Once the lawyer accepts your request and provides a quote, you can proceed with payment to confirm your booking.',
                'category' => 'payments',
                'order' => 9,
            ],
            [
                'question' => 'Can I get a refund?',
                'answer' => 'Refunds are available if the lawyer cancels the consultation or if there are technical issues preventing the consultation. Refund requests must be submitted within 7 days of the consultation date.',
                'category' => 'payments',
                'order' => 10,
            ],
            [
                'question' => 'Do I get a receipt for my payment?',
                'answer' => 'Yes, you will receive an electronic receipt via email immediately after payment. You can also download receipts from your transaction history in your dashboard.',
                'category' => 'payments',
                'order' => 11,
            ],

            // Documents
            [
                'question' => 'Can lawyers help me prepare legal documents?',
                'answer' => 'Yes! Our lawyers can draft, review, and prepare various legal documents including contracts, affidavits, demand letters, and more. Browse our document templates or request custom document preparation.',
                'category' => 'documents',
                'order' => 12,
            ],
            [
                'question' => 'How long does document preparation take?',
                'answer' => 'Document preparation time varies depending on complexity. Simple documents may be ready within 1-3 business days, while complex documents may take 5-7 business days. Your lawyer will provide an estimated timeline.',
                'category' => 'documents',
                'order' => 13,
            ],
            [
                'question' => 'Are my documents secure?',
                'answer' => 'Absolutely. All documents are stored securely using encryption. Only you and your assigned lawyer can access your documents. We comply with data privacy regulations to protect your information.',
                'category' => 'documents',
                'order' => 14,
            ],

            // For Lawyers
            [
                'question' => 'How do I become a lawyer on AbogadoMo?',
                'answer' => 'Click "Register as Lawyer" and complete the registration form. You\'ll need to provide your IBP (Integrated Bar of the Philippines) credentials, professional information, and undergo our verification process.',
                'category' => 'lawyers',
                'order' => 15,
            ],
            [
                'question' => 'How long does verification take?',
                'answer' => 'Lawyer verification typically takes 2-5 business days. We verify your IBP membership, credentials, and professional standing before approving your account.',
                'category' => 'lawyers',
                'order' => 16,
            ],
            [
                'question' => 'How do I receive payments?',
                'answer' => 'Payments are processed through our platform and transferred to your registered bank account. Payouts are processed weekly for completed consultations.',
                'category' => 'lawyers',
                'order' => 17,
            ],
            [
                'question' => 'Can I set my own consultation rates?',
                'answer' => 'Yes, you have full control over your consultation rates. You can set different rates for video consultations, chat consultations, and document preparation services.',
                'category' => 'lawyers',
                'order' => 18,
            ],

            // For Clients
            [
                'question' => 'Do I need to create an account?',
                'answer' => 'Yes, creating an account allows you to book consultations, track your cases, access documents, and communicate with your lawyer securely.',
                'category' => 'clients',
                'order' => 19,
            ],
            [
                'question' => 'How do I choose the right lawyer?',
                'answer' => 'Browse lawyer profiles to view their specializations, experience, ratings, and reviews. You can filter by practice area, location, and availability to find the best match for your needs.',
                'category' => 'clients',
                'order' => 20,
            ],
            [
                'question' => 'Is my information confidential?',
                'answer' => 'Yes, all communications and information shared on our platform are confidential and protected by attorney-client privilege. We use encryption and secure protocols to protect your data.',
                'category' => 'clients',
                'order' => 21,
            ],
            [
                'question' => 'Can I leave a review for my lawyer?',
                'answer' => 'Yes, after your consultation is completed, you can leave a rating and review to help other clients make informed decisions.',
                'category' => 'clients',
                'order' => 22,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
