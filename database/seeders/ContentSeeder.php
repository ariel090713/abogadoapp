<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LegalGuide;
use App\Models\News;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Downloadable;
use App\Models\ContentCategory;
use App\Models\User;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user for author
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        $this->seedLegalGuides($admin);
        $this->seedNews($admin);
        $this->seedBlogs($admin);
        $this->seedEvents();
        $this->seedGalleries();
        $this->seedDownloadables();

        $this->command->info('Content seeded successfully!');
    }

    private function seedLegalGuides($admin)
    {
        $guides = [
            [
                'title' => 'Understanding Your Rights as an Employee in the Philippines',
                'category' => 'labor-law',
                'excerpt' => 'Learn about your fundamental rights as an employee under Philippine labor laws, including minimum wage, working hours, and benefits.',
                'content' => '<h2>Employee Rights in the Philippines</h2>
                <p>The Philippine Labor Code protects employees through various provisions. Every worker has the right to security of tenure, fair wages, safe working conditions, and the right to self-organization.</p>
                <h3>Minimum Wage and Compensation</h3>
                <p>Employers must pay at least the minimum wage set by regional wage boards. Overtime work must be compensated at 125% of regular pay, while work on rest days and holidays has higher rates.</p>
                
                <h3>Working Hours and Rest Periods</h3>
                <p>The normal working hours should not exceed 8 hours a day. Employees are entitled to at least 24 consecutive hours of rest after every 6 consecutive working days.</p>
                
                <h3>Leave Benefits</h3>
                <p>Employees are entitled to service incentive leave of at least 5 days after one year of service. Female employees are entitled to maternity leave, while both parents can avail of parental leave.</p>
                
                <h3>Security of Tenure</h3>
                <p>Employees cannot be dismissed without just or authorized cause and due process. Illegal dismissal entitles the employee to reinstatement and back wages.</p>',
            ],
            [
                'title' => 'How to File a Small Claims Case in the Philippines',
                'category' => 'Civil Law',
                'excerpt' => 'A step-by-step guide to filing small claims cases for amounts up to ₱400,000 without needing a lawyer.',
                'content' => '<h2>Small Claims Procedure</h2>
                <p>The Small Claims Court provides a simplified and inexpensive procedure for resolving money claims not exceeding ₱400,000.</p>
                
                <h3>What Cases Can Be Filed</h3>
                <p>Small claims include collection of money, enforcement of barangay amicable settlement, and claims arising from contracts of sale, lease, services, and loans.</p>
                
                <h3>Filing Requirements</h3>
                <p>You need to prepare: Statement of Claim form, supporting documents (contracts, receipts, checks), and filing fee. No lawyer is required.</p>
                
                <h3>The Process</h3>
                <p>1. File the Statement of Claim at the Metropolitan Trial Court<br>
                2. Court summons the defendant<br>
                3. Hearing is scheduled (usually within 30 days)<br>
                4. Both parties present evidence<br>
                5. Judge renders decision immediately or within 24 hours</p>
                
                <h3>Important Notes</h3>
                <p>Decisions are final and immediately executory. No appeals are allowed except on jurisdictional grounds.</p>',
            ],
            [
                'title' => 'Property Rights and Land Ownership in the Philippines',
                'category' => 'Property Law',
                'excerpt' => 'Essential information about buying, selling, and owning property in the Philippines, including restrictions and requirements.',
                'content' => '<h2>Land Ownership in the Philippines</h2>
                <p>The Philippine Constitution restricts land ownership to Filipino citizens and corporations with at least 60% Filipino ownership.</p>
                
                <h3>Types of Land Titles</h3>
                <p>Original Certificate of Title (OCT) is issued for land registered for the first time. Transfer Certificate of Title (TCT) is issued for subsequent transfers. Condominium Certificate of Title (CCT) is for condominium units.</p>
                
                <h3>Buying Process</h3>
                <p>1. Verify the title at the Registry of Deeds<br>
                2. Check for liens and encumbrances<br>
                3. Execute Deed of Absolute Sale<br>
                4. Pay transfer taxes and fees<br>
                5. Register the sale and obtain new title</p>
                
                <h3>Taxes and Fees</h3>
                <p>Capital Gains Tax (6% of selling price or zonal value), Documentary Stamp Tax (1.5%), Transfer Tax (0.5-0.75%), and Registration Fees must be paid.</p>
                
                <h3>Foreign Ownership</h3>
                <p>Foreigners cannot own land but can own condominium units (up to 40% of total units) and buildings on leased land.</p>',
            ],
            [
                'title' => 'Starting a Business in the Philippines: Legal Requirements',
                'category' => 'Business Law',
                'excerpt' => 'Complete guide to the legal requirements for starting and registering a business in the Philippines.',
                'content' => '<h2>Business Registration in the Philippines</h2>
                <p>Starting a business requires compliance with various government agencies and securing necessary permits and licenses.</p>
                
                <h3>Types of Business Structures</h3>
                <p>Sole Proprietorship is the simplest form owned by one person. Partnership involves two or more persons. Corporation is a separate legal entity with limited liability.</p>
                
                <h3>Registration Steps</h3>
                <p>1. Register business name with DTI (sole proprietorship) or SEC (corporation/partnership)<br>
                2. Secure Barangay Clearance<br>
                3. Register with BIR and get TIN<br>
                4. Apply for Mayor\'s Permit<br>
                5. Register with SSS, PhilHealth, and Pag-IBIG</p>
                
                <h3>Required Documents</h3>
                <p>Valid IDs, proof of address, business name registration, lease contract or proof of business location, and barangay clearance.</p>
                
                <h3>Ongoing Compliance</h3>
                <p>File monthly/quarterly tax returns, renew business permits annually, maintain proper books of accounts, and comply with labor laws if hiring employees.</p>',
            ],
            [
                'title' => 'Family Law: Marriage, Annulment, and Legal Separation',
                'category' => 'Family Law',
                'excerpt' => 'Understanding marriage laws, grounds for annulment, and the process of legal separation in the Philippines.',
                'content' => '<h2>Marriage and Family Law</h2>
                <p>The Family Code of the Philippines governs marriage, annulment, legal separation, and family relations.</p>
                
                <h3>Marriage Requirements</h3>
                <p>Both parties must be at least 18 years old, not related within prohibited degrees, and not currently married. Marriage license must be obtained 10 days before the ceremony.</p>
                
                <h3>Grounds for Annulment</h3>
                <p>Lack of parental consent (if under 21), insanity, fraud, force or intimidation, physical incapacity, and sexually transmissible disease are grounds for annulment.</p>
                
                <h3>Declaration of Nullity</h3>
                <p>Psychological incapacity, marriages without license (except exceptions), bigamous marriages, and incestuous marriages are void from the beginning.</p>
                
                <h3>Legal Separation</h3>
                <p>Grounds include repeated physical violence, drug addiction, sexual infidelity, abandonment, and attempt on life of spouse. The marriage bond remains but spouses live separately.</p>
                
                <h3>Property Relations</h3>
                <p>Absolute Community of Property is the default regime. Conjugal Partnership of Gains and Complete Separation of Property are alternatives.</p>',
            ],
            [
                'title' => 'Criminal Law Basics: Your Rights When Arrested',
                'category' => 'Criminal Law',
                'excerpt' => 'Know your constitutional rights when arrested and the criminal justice process in the Philippines.',
                'content' => '<h2>Rights of the Accused</h2>
                <p>The Philippine Constitution guarantees fundamental rights to persons accused of crimes.</p>
                
                <h3>Miranda Rights</h3>
                <p>You have the right to remain silent. Anything you say can be used against you. You have the right to a lawyer. If you cannot afford one, the state will provide one.</p>
                
                <h3>Warrantless Arrest</h3>
                <p>Valid only when: person is caught in the act (in flagrante delicto), just committed a crime (hot pursuit), or is an escaped prisoner.</p>
                
                <h3>Inquest Proceedings</h3>
                <p>For warrantless arrests, inquest must be conducted within 12-36 hours. The prosecutor determines if there is probable cause to file charges.</p>
                
                <h3>Bail Rights</h3>
                <p>All persons are entitled to bail except those charged with capital offenses when evidence of guilt is strong. Bail amount should not be excessive.</p>
                
                <h3>Right to Speedy Trial</h3>
                <p>Cases must be decided within 90 days from submission. Unreasonable delays violate the right to speedy disposition of cases.</p>',
            ],
            [
                'title' => 'Cybercrime Law: What You Need to Know',
                'category' => 'Technology Law',
                'excerpt' => 'Understanding the Cybercrime Prevention Act and how it affects online activities in the Philippines.',
                'content' => '<h2>Cybercrime Prevention Act of 2012</h2>
                <p>Republic Act 10175 penalizes cybercrimes and provides law enforcement with tools to combat online criminal activities.</p>
                
                <h3>Covered Offenses</h3>
                <p>Illegal access to computer systems, data interference, system interference, misuse of devices, cyber-squatting, and computer-related fraud are prohibited.</p>
                
                <h3>Content-Related Offenses</h3>
                <p>Cybersex, child pornography, unsolicited commercial communications (spam), and libel committed through computer systems are punishable.</p>
                
                <h3>Online Libel</h3>
                <p>Defamatory statements posted online carry higher penalties than traditional libel. The prescriptive period is 12 years from publication.</p>
                
                <h3>Data Privacy</h3>
                <p>The law works with the Data Privacy Act to protect personal information. Unauthorized access or disclosure of personal data is punishable.</p>
                
                <h3>Penalties</h3>
                <p>Imprisonment ranges from 6 years to life imprisonment depending on the offense. Fines range from ₱200,000 to ₱10,000,000.</p>',
            ],
            [
                'title' => 'Inheritance and Estate Planning in the Philippines',
                'category' => 'Civil Law',
                'excerpt' => 'Guide to wills, estate settlement, and inheritance rights under Philippine law.',
                'content' => '<h2>Succession and Estate Planning</h2>
                <p>The Civil Code governs succession, ensuring orderly transfer of property upon death while protecting compulsory heirs.</p>
                
                <h3>Types of Succession</h3>
                <p>Compulsory succession protects legitimate children, spouse, and parents. Testamentary succession is through a will. Intestate succession applies when there is no will.</p>
                
                <h3>Making a Valid Will</h3>
                <p>Testator must be at least 18 years old and of sound mind. Will must be in writing, signed by testator and witnesses. Notarial will requires 3 witnesses and notarization.</p>
                
                <h3>Legitime</h3>
                <p>Compulsory heirs are entitled to their legitime (reserved portion). Legitimate children get 1/2 of the estate. Surviving spouse gets a portion depending on who else survives.</p>
                
                <h3>Estate Settlement</h3>
                <p>File petition for probate (if with will) or intestate settlement. Pay estate tax within one year. Distribute properties to heirs after court approval.</p>
                
                <h3>Estate Tax</h3>
                <p>6% of net estate exceeding ₱5,000,000. Must be paid within one year from death to avoid penalties and interest.</p>',
            ],
        ];

        foreach ($guides as $guide) {
            LegalGuide::create([
                'title' => $guide['title'],
                'category' => $guide['category'],
                'excerpt' => $guide['excerpt'],
                'content' => $guide['content'],
                'is_published' => true,
                'views' => rand(50, 500),
                'author_id' => $admin->id,
            ]);
        }

        $this->command->info('Legal Guides seeded: ' . count($guides));
    }

    private function seedNews($admin)
    {
        $news = [
            [
                'title' => 'Supreme Court Issues New Guidelines on Bail Applications',
                'excerpt' => 'The Supreme Court has released updated guidelines for bail applications in criminal cases, streamlining the process and clarifying requirements.',
                'content' => '<p>The Supreme Court has issued Administrative Circular No. 2026-01 providing new guidelines for bail applications in criminal cases nationwide.</p>
                
                <p>The circular aims to standardize bail procedures across all courts and ensure consistent application of constitutional rights. Key changes include mandatory hearings within 3 days of filing and stricter documentation requirements.</p>
                
                <p>Chief Justice emphasized that the new guidelines balance the right to liberty with public safety concerns. Courts must now provide written justification for bail denials.</p>
                
                <p>The circular takes effect immediately and applies to all pending and future bail applications. Training programs for judges and court personnel will be conducted nationwide.</p>',
            ],
            [
                'title' => 'Congress Passes Amendments to Labor Code',
                'excerpt' => 'Significant amendments to the Labor Code have been passed, expanding employee benefits and strengthening worker protections.',
                'content' => '<p>The House of Representatives and Senate have approved amendments to the Labor Code, introducing expanded benefits for workers and stricter penalties for labor violations.</p>
                
                <p>Key provisions include increased maternity leave from 105 to 120 days, mandatory paternity leave extension to 14 days, and enhanced protection against illegal dismissal.</p>
                
                <p>The amendments also introduce stricter penalties for employers who violate labor standards, with fines ranging from ₱100,000 to ₱500,000 depending on the violation.</p>
                
                <p>Labor groups have welcomed the amendments as a significant step toward better worker protection. The law awaits the President\'s signature and will take effect 15 days after publication.</p>',
            ],
            [
                'title' => 'New Data Privacy Regulations for Online Businesses',
                'excerpt' => 'The National Privacy Commission issues new regulations requiring stricter data protection measures for e-commerce and online service providers.',
                'content' => '<p>The National Privacy Commission (NPC) has released new implementing rules requiring online businesses to implement enhanced data protection measures.</p>
                
                <p>Under NPC Circular 2026-02, e-commerce platforms and online service providers must conduct regular data protection impact assessments and appoint data protection officers.</p>
                
                <p>The regulations mandate clear consent mechanisms, data breach notification within 72 hours, and regular security audits. Non-compliance may result in fines up to ₱5,000,000.</p>
                
                <p>Businesses have 6 months to comply with the new requirements. The NPC will conduct training sessions and provide compliance guides for affected entities.</p>',
            ],
            [
                'title' => 'Court of Appeals Rules on Condominium Ownership Rights',
                'excerpt' => 'Landmark decision clarifies rights of condominium unit owners regarding common areas and association fees.',
                'content' => '<p>The Court of Appeals has issued a landmark decision in the case of Homeowners Association vs. Unit Owners, clarifying the extent of condominium ownership rights.</p>
                
                <p>The court ruled that unit owners have co-ownership rights over common areas and cannot be arbitrarily denied access. Association fees must be reasonable and properly accounted for.</p>
                
                <p>The decision also addressed the validity of house rules, stating that rules must not violate constitutional rights and must be approved by majority of unit owners.</p>
                
                <p>Legal experts say the ruling will significantly impact condominium governance and provide clearer guidelines for resolving disputes between associations and unit owners.</p>',
            ],
            [
                'title' => 'DOJ Launches Online Case Tracking System',
                'excerpt' => 'The Department of Justice introduces a new online platform allowing parties to track the status of cases filed with prosecutors\' offices.',
                'content' => '<p>The Department of Justice has launched the DOJ Case Tracker, an online system that allows complainants and respondents to monitor case progress in real-time.</p>
                
                <p>The system provides updates on preliminary investigations, resolutions, and case filings. Users can access the platform using their case numbers and verification codes.</p>
                
                <p>Justice Secretary stated that the initiative aims to promote transparency and reduce the need for physical inquiries at prosecutors\' offices. The system also sends SMS and email notifications for important case developments.</p>
                
                <p>The platform is currently available for Metro Manila prosecutors\' offices and will be rolled out nationwide by the end of the year.</p>',
            ],
            [
                'title' => 'SEC Implements Faster Business Registration Process',
                'excerpt' => 'The Securities and Exchange Commission reduces business registration time to 3 days with new streamlined procedures.',
                'content' => '<p>The Securities and Exchange Commission has implemented new procedures that reduce business registration time from 2 weeks to just 3 working days.</p>
                
                <p>The streamlined process includes online submission of documents, automated name verification, and digital certificate issuance. Applicants can now track their applications in real-time.</p>
                
                <p>SEC Chairperson announced that the initiative is part of the government\'s ease of doing business program. The new system has already processed over 5,000 applications since its launch.</p>
                
                <p>Entrepreneurs and business groups have praised the development, noting that faster registration will encourage more people to formalize their businesses and contribute to economic growth.</p>',
            ],
            [
                'title' => 'Anti-Discrimination Law Signed into Law',
                'excerpt' => 'President signs comprehensive anti-discrimination law protecting individuals from discrimination based on various grounds.',
                'content' => '<p>The President has signed into law the Anti-Discrimination Act, providing comprehensive protection against discrimination in employment, education, and public services.</p>
                
                <p>The law prohibits discrimination based on age, sex, gender identity, sexual orientation, religion, ethnicity, disability, and other grounds. Violators face imprisonment and fines.</p>
                
                <p>The law establishes an Anti-Discrimination Commission to investigate complaints and ensure compliance. Victims can file complaints within one year of the discriminatory act.</p>
                
                <p>Civil society groups have hailed the law as a historic step toward equality and social justice. The law takes effect 15 days after publication in major newspapers.</p>',
            ],
            [
                'title' => 'Supreme Court Allows Electronic Filing of Court Documents',
                'excerpt' => 'New rules permit electronic filing of pleadings and documents in all courts nationwide, modernizing court procedures.',
                'content' => '<p>The Supreme Court has approved rules allowing electronic filing of court documents, marking a significant shift toward digital court procedures.</p>
                
                <p>Under the new rules, lawyers and litigants can file pleadings, motions, and other documents through the eCourt system. Electronic signatures are now recognized as valid.</p>
                
                <p>The system includes features for online payment of filing fees, automatic case number generation, and digital service of documents. Physical filing remains available as an alternative.</p>
                
                <p>The Supreme Court stated that the initiative will reduce delays, lower costs, and improve access to justice. Full implementation is expected within 12 months.</p>',
            ],
        ];

        foreach ($news as $item) {
            News::create([
                'title' => $item['title'],
                'excerpt' => $item['excerpt'],
                'content' => $item['content'],
                'is_published' => true,
                'views' => rand(100, 800),
                'author_id' => $admin->id,
            ]);
        }

        $this->command->info('News seeded: ' . count($news));
    }

    private function seedBlogs($admin)
    {
        $blogs = [
            [
                'title' => '5 Common Legal Mistakes Small Business Owners Make',
                'category' => 'Business',
                'excerpt' => 'Avoid these common legal pitfalls that could cost your small business time, money, and reputation.',
                'content' => '<h2>Legal Mistakes to Avoid</h2>
                <p>Running a small business in the Philippines comes with numerous legal obligations. Here are five common mistakes and how to avoid them.</p>
                
                <h3>1. Operating Without Proper Registration</h3>
                <p>Many entrepreneurs start selling products or services without registering their business. This exposes you to penalties and prevents you from issuing official receipts. Always register with DTI or SEC, BIR, and your local government.</p>
                
                <h3>2. Not Having Written Contracts</h3>
                <p>Verbal agreements are difficult to enforce. Always put agreements in writing, whether with suppliers, customers, or employees. A simple contract can prevent costly disputes.</p>
                
                <h3>3. Ignoring Labor Laws</h3>
                <p>Misclassifying employees as contractors, not paying proper benefits, or failing to register with SSS, PhilHealth, and Pag-IBIG can result in heavy penalties. Understand your obligations as an employer.</p>
                
                <h3>4. Neglecting Intellectual Property Protection</h3>
                <p>Not trademarking your brand or protecting your innovations leaves you vulnerable to copycats. Register your trademarks and patents with the IPO.</p>
                
                <h3>5. Poor Record Keeping</h3>
                <p>Inadequate financial records can lead to tax problems and make it difficult to track business performance. Maintain proper books of accounts and keep all receipts and invoices.</p>
                
                <p>Consulting with a lawyer early can help you avoid these mistakes and set your business up for success.</p>',
            ],
            [
                'title' => 'Understanding Your Rights as a Tenant in the Philippines',
                'category' => 'Property',
                'excerpt' => 'What every tenant should know about their rights and obligations under Philippine rental laws.',
                'content' => '<h2>Tenant Rights and Responsibilities</h2>
                <p>Whether renting an apartment, house, or commercial space, knowing your rights as a tenant is essential.</p>
                
                <h3>Security Deposit Limits</h3>
                <p>Landlords can require a maximum of 2 months advance rent and 2 months deposit for residential properties. Any amount beyond this is illegal.</p>
                
                <h3>Rent Increases</h3>
                <p>For residential units, rent can only be increased once a year and must not exceed 7% for Metro Manila or 10% for other areas, unless otherwise agreed in writing.</p>
                
                <h3>Eviction Grounds</h3>
                <p>Landlords can only evict tenants for specific reasons: non-payment of rent, violation of contract terms, expiration of lease, or legitimate need for personal use. Proper notice must be given.</p>
                
                <h3>Repairs and Maintenance</h3>
                <p>Landlords must maintain the property in habitable condition. Tenants are responsible for minor repairs and must not make major alterations without permission.</p>
                
                <h3>Return of Deposit</h3>
                <p>Upon lease termination, deposits must be returned within 30 days, minus any legitimate deductions for damages beyond normal wear and tear.</p>
                
                <p>Always have a written lease agreement and document the property\'s condition at move-in to protect your rights.</p>',
            ],
            [
                'title' => 'How to Protect Yourself from Online Scams',
                'category' => 'Consumer Protection',
                'excerpt' => 'Practical tips to avoid falling victim to online scams and what to do if you\'ve been scammed.',
                'content' => '<h2>Staying Safe Online</h2>
                <p>Online scams are increasingly sophisticated. Here\'s how to protect yourself and your money.</p>
                
                <h3>Common Online Scams</h3>
                <p>Phishing emails, fake online shops, investment scams, romance scams, and job offer scams are prevalent. Scammers often create urgency to pressure victims into acting quickly.</p>
                
                <h3>Red Flags to Watch For</h3>
                <p>Too-good-to-be-true offers, requests for advance payment, poor grammar and spelling, pressure to act immediately, and requests for personal information are warning signs.</p>
                
                <h3>Protection Measures</h3>
                <p>Verify websites before purchasing, use secure payment methods, never share OTPs or passwords, check seller reviews, and be skeptical of unsolicited messages.</p>
                
                <h3>If You\'ve Been Scammed</h3>
                <p>Report to your bank immediately to attempt transaction reversal. File a complaint with the NBI Cybercrime Division or PNP Anti-Cybercrime Group. Report to DTI for consumer protection cases.</p>
                
                <h3>Legal Remedies</h3>
                <p>You can file criminal charges for estafa or violations of the Cybercrime Prevention Act. Civil cases for damages can also be filed. Gather all evidence: screenshots, receipts, and communications.</p>
                
                <p>Prevention is always better than cure. Stay vigilant and verify before you trust.</p>',
            ],
            [
                'title' => 'Estate Planning: Why You Need a Will',
                'category' => 'Family Law',
                'excerpt' => 'The importance of having a will and how it protects your loved ones after you\'re gone.',
                'content' => '<h2>The Importance of Estate Planning</h2>
                <p>Many Filipinos avoid discussing death and estate planning, but having a will is one of the most important things you can do for your family.</p>
                
                <h3>Why You Need a Will</h3>
                <p>A will ensures your wishes are followed, minimizes family conflicts, speeds up estate settlement, and allows you to choose guardians for minor children. Without a will, intestate succession rules apply.</p>
                
                <h3>What You Can Include</h3>
                <p>You can distribute your free portion (portion not reserved for compulsory heirs) as you wish, name an executor, specify funeral arrangements, and create trusts for beneficiaries.</p>
                
                <h3>Compulsory Heirs</h3>
                <p>Philippine law protects compulsory heirs (children, spouse, parents) by reserving a portion of your estate for them. You cannot completely disinherit them without valid grounds.</p>
                
                <h3>Making Your Will Valid</h3>
                <p>A notarial will must be in writing, signed by you and three witnesses, and notarized. A holographic will must be entirely handwritten, dated, and signed by you.</p>
                
                <h3>Updating Your Will</h3>
                <p>Review your will every few years or after major life events (marriage, birth of children, acquisition of significant assets). You can revoke or amend your will anytime.</p>
                
                <p>Consult a lawyer to ensure your will is valid and reflects your wishes. The peace of mind is worth the investment.</p>',
            ],
            [
                'title' => 'Navigating Separation: Legal Options for Couples',
                'category' => 'Family Law',
                'excerpt' => 'Understanding the differences between annulment, declaration of nullity, and legal separation in the Philippines.',
                'content' => '<h2>Options for Ending a Marriage</h2>
                <p>The Philippines does not allow absolute divorce, but there are legal options for couples who can no longer stay together.</p>
                
                <h3>Declaration of Nullity</h3>
                <p>This declares that a valid marriage never existed. Grounds include psychological incapacity, lack of marriage license, bigamy, and incestuous marriages. The process typically takes 2-4 years.</p>
                
                <h3>Annulment</h3>
                <p>This voids a marriage that was valid at the time of celebration but had defects. Grounds include lack of parental consent, fraud, force, and physical incapacity. Duration is similar to nullity cases.</p>
                
                <h3>Legal Separation</h3>
                <p>This allows spouses to live separately but remain married. Grounds include physical violence, drug addiction, infidelity, and abandonment. Property is divided but remarriage is not allowed.</p>
                
                <h3>Costs and Process</h3>
                <p>Nullity and annulment cases cost ₱200,000-₱500,000 including lawyer\'s fees, court fees, and psychological evaluation. Legal separation is generally less expensive.</p>
                
                <h3>Effects on Children</h3>
                <p>Children remain legitimate regardless of the outcome. Custody, support, and visitation rights must be determined by the court based on the children\'s best interests.</p>
                
                <p>Each case is unique. Consult a family law attorney to understand which option is appropriate for your situation.</p>',
            ],
            [
                'title' => 'Employment Contracts: What to Look For Before Signing',
                'category' => 'Labor',
                'excerpt' => 'Key provisions to review in your employment contract to protect your rights and interests.',
                'content' => '<h2>Understanding Your Employment Contract</h2>
                <p>Before signing an employment contract, carefully review these important provisions.</p>
                
                <h3>Job Description and Duties</h3>
                <p>Ensure your role, responsibilities, and reporting structure are clearly defined. Vague descriptions can lead to unreasonable work assignments.</p>
                
                <h3>Compensation and Benefits</h3>
                <p>Verify your salary, allowances, bonuses, and benefits. Check if 13th month pay, leave credits, and government-mandated benefits are included.</p>
                
                <h3>Working Hours and Overtime</h3>
                <p>Confirm your work schedule, rest days, and overtime compensation. Ensure compliance with the 8-hour work day rule.</p>
                
                <h3>Probationary Period</h3>
                <p>The maximum probationary period is 6 months. The contract should specify performance standards for regularization.</p>
                
                <h3>Termination Clauses</h3>
                <p>Understand the grounds for termination and notice requirements. Be wary of overly broad termination clauses that violate security of tenure.</p>
                
                <h3>Non-Compete and Confidentiality</h3>
                <p>Review restrictions on working for competitors or starting similar businesses. Ensure these are reasonable in scope and duration.</p>
                
                <h3>Dispute Resolution</h3>
                <p>Check if there are arbitration clauses or requirements to exhaust company grievance procedures before filing cases.</p>
                
                <p>If anything is unclear or seems unfair, consult a labor lawyer before signing. Your signature binds you to the terms.</p>',
            ],
            [
                'title' => 'Intellectual Property 101: Protecting Your Creative Work',
                'category' => 'Intellectual Property',
                'excerpt' => 'A beginner\'s guide to copyrights, trademarks, and patents in the Philippines.',
                'content' => '<h2>Protecting Your Intellectual Property</h2>
                <p>Whether you\'re an artist, inventor, or entrepreneur, understanding IP protection is crucial.</p>
                
                <h3>Copyright Protection</h3>
                <p>Copyright automatically protects original literary, artistic, and musical works upon creation. Registration with the National Library or IPO provides additional evidence of ownership.</p>
                
                <h3>Trademark Registration</h3>
                <p>Trademarks protect brand names, logos, and slogans. Registration with the IPO gives you exclusive rights to use the mark for 10 years, renewable indefinitely.</p>
                
                <h3>Patent Protection</h3>
                <p>Patents protect inventions and innovations. Utility patents last 20 years, while industrial designs are protected for 15 years. Registration is required.</p>
                
                <h3>Trade Secrets</h3>
                <p>Confidential business information can be protected through non-disclosure agreements and internal security measures. No registration is needed.</p>
                
                <h3>Enforcement</h3>
                <p>If someone infringes your IP rights, you can file criminal charges, civil cases for damages, or administrative complaints with the IPO. Customs can also seize counterfeit goods.</p>
                
                <h3>International Protection</h3>
                <p>Philippine IP rights are territorial. For international protection, register in other countries or use international treaties like the Madrid Protocol for trademarks.</p>
                
                <p>IP protection is an investment in your creative and business assets. Act early to secure your rights.</p>',
            ],
            [
                'title' => 'Data Privacy Compliance for Businesses',
                'category' => 'Technology',
                'excerpt' => 'What businesses need to know about complying with the Data Privacy Act of 2012.',
                'content' => '<h2>Data Privacy Compliance</h2>
                <p>The Data Privacy Act requires businesses that collect personal information to implement proper safeguards and comply with privacy principles.</p>
                
                <h3>Who Must Comply</h3>
                <p>All businesses that process personal information of Filipino citizens, whether in the Philippines or abroad, must comply. This includes customer data, employee records, and supplier information.</p>
                
                <h3>Key Requirements</h3>
                <p>Obtain consent before collecting data, implement security measures, appoint a Data Protection Officer (if required), register with the NPC, and report data breaches within 72 hours.</p>
                
                <h3>Privacy Principles</h3>
                <p>Collect only necessary data, use it only for stated purposes, keep it accurate and updated, store it securely, and retain it only as long as needed.</p>
                
                <h3>Individual Rights</h3>
                <p>Individuals have the right to access their data, correct inaccuracies, object to processing, and request deletion. Businesses must have procedures to honor these rights.</p>
                
                <h3>Penalties for Non-Compliance</h3>
                <p>Violations can result in imprisonment of 1-6 years and fines of ₱500,000 to ₱5,000,000. The NPC can also issue compliance orders and impose administrative fines.</p>
                
                <h3>Practical Steps</h3>
                <p>Conduct a data inventory, create a privacy policy, train employees, implement security measures, and establish breach response procedures.</p>
                
                <p>Data privacy is not just a legal requirement—it\'s good business practice that builds customer trust.</p>',
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::create([
                'title' => $blog['title'],
                'category' => $blog['category'],
                'excerpt' => $blog['excerpt'],
                'content' => $blog['content'],
                'is_published' => true,
                'views' => rand(80, 600),
                'author_id' => $admin->id,
            ]);
        }

        $this->command->info('Blogs seeded: ' . count($blogs));
    }

    private function seedEvents()
    {
        $events = [
            [
                'title' => 'Free Legal Consultation Webinar: Labor Rights',
                'event_type' => 'webinar',
                'description' => 'Join our free webinar on employee rights and labor law compliance. Q&A session included.',
                'content' => '<h2>About This Webinar</h2>
                <p>This free webinar will cover essential topics in labor law, including employee rights, employer obligations, and recent updates to the Labor Code.</p>
                
                <h3>Topics Covered</h3>
                <ul>
                    <li>Employee rights and benefits</li>
                    <li>Termination and separation pay</li>
                    <li>Handling labor disputes</li>
                    <li>Recent labor law updates</li>
                </ul>
                
                <h3>Who Should Attend</h3>
                <p>Employees, HR professionals, business owners, and anyone interested in understanding labor rights in the Philippines.</p>
                
                <h3>Speaker</h3>
                <p>Atty. Maria Santos, labor law specialist with 15 years of experience in employment litigation and compliance.</p>',
                'event_date' => now()->addDays(15)->setTime(14, 0),
                'location' => 'Online',
                'meeting_link' => 'https://zoom.us/j/example123',
                'max_participants' => 100,
                'registered_count' => 45,
            ],
            [
                'title' => 'Small Business Legal Compliance Workshop',
                'event_type' => 'workshop',
                'description' => 'Hands-on workshop on legal requirements for starting and running a small business in the Philippines.',
                'content' => '<h2>Workshop Overview</h2>
                <p>This interactive workshop will guide you through the legal requirements of starting and operating a small business in the Philippines.</p>
                
                <h3>What You\'ll Learn</h3>
                <ul>
                    <li>Business registration process</li>
                    <li>Tax compliance requirements</li>
                    <li>Employment law basics</li>
                    <li>Contract drafting essentials</li>
                    <li>Intellectual property protection</li>
                </ul>
                
                <h3>Workshop Format</h3>
                <p>Interactive sessions with practical exercises, sample documents, and Q&A. Participants will receive a compliance checklist and template contracts.</p>
                
                <h3>Requirements</h3>
                <p>Bring your laptop and any business documents you want to review. Limited to 30 participants for personalized attention.</p>',
                'event_date' => now()->addDays(22)->setTime(9, 0),
                'location' => 'Makati Business Center, Ayala Avenue, Makati City',
                'meeting_link' => null,
                'max_participants' => 30,
                'registered_count' => 18,
            ],
            [
                'title' => 'Family Law Seminar: Marriage, Separation, and Inheritance',
                'event_type' => 'seminar',
                'description' => 'Comprehensive seminar on family law topics including marriage, annulment, and estate planning.',
                'content' => '<h2>Seminar Details</h2>
                <p>This seminar provides comprehensive coverage of family law topics that affect every Filipino family.</p>
                
                <h3>Topics</h3>
                <ul>
                    <li>Marriage requirements and property relations</li>
                    <li>Annulment vs. declaration of nullity</li>
                    <li>Legal separation and its effects</li>
                    <li>Child custody and support</li>
                    <li>Wills and estate planning</li>
                    <li>Inheritance rights and legitime</li>
                </ul>
                
                <h3>Speakers</h3>
                <p>Panel of family law experts including Atty. Juan dela Cruz and Atty. Ana Reyes, both with extensive experience in family law litigation.</p>
                
                <h3>Certificate</h3>
                <p>Participants will receive a certificate of attendance and seminar materials including sample documents and legal guides.</p>',
                'event_date' => now()->addDays(30)->setTime(13, 0),
                'location' => 'Online',
                'meeting_link' => 'https://zoom.us/j/example456',
                'max_participants' => 150,
                'registered_count' => 67,
            ],
            [
                'title' => 'Real Estate Law Forum: Buying and Selling Property',
                'event_type' => 'forum',
                'description' => 'Interactive forum on real estate transactions, property rights, and common issues in property deals.',
                'content' => '<h2>Forum Overview</h2>
                <p>This interactive forum brings together real estate lawyers, brokers, and property buyers to discuss common issues and best practices in property transactions.</p>
                
                <h3>Discussion Topics</h3>
                <ul>
                    <li>Due diligence in property purchases</li>
                    <li>Title verification and transfer</li>
                    <li>Taxes and fees in property transactions</li>
                    <li>Common property disputes</li>
                    <li>Condominium ownership rights</li>
                    <li>Foreign ownership restrictions</li>
                </ul>
                
                <h3>Format</h3>
                <p>Panel discussion followed by open forum. Participants can submit questions in advance or ask during the event.</p>
                
                <h3>Who Should Attend</h3>
                <p>Property buyers, sellers, real estate agents, and anyone interested in real estate law.</p>',
                'event_date' => now()->addDays(45)->setTime(15, 0),
                'location' => 'BGC Conference Center, Bonifacio Global City, Taguig',
                'meeting_link' => null,
                'max_participants' => 80,
                'registered_count' => 32,
            ],
            [
                'title' => 'Cybercrime and Data Privacy: Protecting Your Digital Rights',
                'event_type' => 'webinar',
                'description' => 'Learn about cybercrime laws, data privacy rights, and how to protect yourself online.',
                'content' => '<h2>Webinar Description</h2>
                <p>In this digital age, understanding your rights and obligations online is crucial. This webinar covers cybercrime laws and data privacy regulations.</p>
                
                <h3>Topics</h3>
                <ul>
                    <li>Cybercrime Prevention Act overview</li>
                    <li>Online libel and defamation</li>
                    <li>Data Privacy Act compliance</li>
                    <li>Protecting personal information online</li>
                    <li>Reporting cybercrimes</li>
                    <li>Legal remedies for victims</li>
                </ul>
                
                <h3>Speakers</h3>
                <p>Atty. Carlos Mendoza, cybercrime law specialist, and Atty. Lisa Tan, data privacy expert and certified DPO.</p>
                
                <h3>Interactive Session</h3>
                <p>Live Q&A session where you can ask questions about specific situations. Case studies and practical tips will be shared.</p>',
                'event_date' => now()->addDays(38)->setTime(16, 0),
                'location' => 'Online',
                'meeting_link' => 'https://zoom.us/j/example789',
                'max_participants' => 120,
                'registered_count' => 54,
            ],
            [
                'title' => 'Legal Clinic: Free Consultation Day',
                'event_type' => 'consultation',
                'description' => 'Free one-on-one legal consultations with volunteer lawyers. First come, first served.',
                'content' => '<h2>Free Legal Clinic</h2>
                <p>AbogadoMo is hosting a free legal clinic where you can get one-on-one consultations with experienced lawyers.</p>
                
                <h3>Available Services</h3>
                <ul>
                    <li>Legal advice on various matters</li>
                    <li>Document review</li>
                    <li>Referrals to appropriate agencies</li>
                    <li>Information on legal procedures</li>
                </ul>
                
                <h3>How It Works</h3>
                <p>Walk-in basis, first come first served. Each consultation lasts 20-30 minutes. Bring relevant documents for review.</p>
                
                <h3>Areas of Law</h3>
                <p>Family law, labor law, civil law, criminal law, and property law. Volunteer lawyers from various specializations will be available.</p>
                
                <h3>Important Note</h3>
                <p>This is for initial consultation only. For cases requiring representation, lawyers may offer to take your case or provide referrals.</p>',
                'event_date' => now()->addDays(20)->setTime(9, 0),
                'location' => 'AbogadoMo Office, Ortigas Center, Pasig City',
                'meeting_link' => null,
                'max_participants' => 50,
                'registered_count' => 38,
            ],
            [
                'title' => 'Intellectual Property Rights for Creatives and Entrepreneurs',
                'event_type' => 'workshop',
                'description' => 'Workshop on protecting your creative works, brands, and innovations through IP registration.',
                'content' => '<h2>IP Protection Workshop</h2>
                <p>Learn how to protect your creative works, business brands, and innovations through proper intellectual property registration and enforcement.</p>
                
                <h3>Workshop Content</h3>
                <ul>
                    <li>Copyright basics and registration</li>
                    <li>Trademark registration process</li>
                    <li>Patent application procedures</li>
                    <li>Trade secret protection</li>
                    <li>IP enforcement and litigation</li>
                    <li>International IP protection</li>
                </ul>
                
                <h3>Hands-On Activities</h3>
                <p>Participants will learn how to conduct trademark searches, prepare basic IP applications, and draft non-disclosure agreements.</p>
                
                <h3>Materials Provided</h3>
                <p>Sample applications, template agreements, and step-by-step guides for IP registration with the IPO.</p>',
                'event_date' => now()->addDays(52)->setTime(10, 0),
                'location' => 'Online',
                'meeting_link' => 'https://zoom.us/j/example321',
                'max_participants' => 60,
                'registered_count' => 28,
            ],
            [
                'title' => 'Criminal Law Basics: Understanding Your Rights',
                'event_type' => 'seminar',
                'description' => 'Seminar on criminal law fundamentals, rights of the accused, and the criminal justice process.',
                'content' => '<h2>Criminal Law Seminar</h2>
                <p>This seminar provides essential knowledge about criminal law, your constitutional rights, and how the criminal justice system works.</p>
                
                <h3>Topics Covered</h3>
                <ul>
                    <li>Rights of the accused</li>
                    <li>Arrest procedures and warrants</li>
                    <li>Bail and detention</li>
                    <li>Criminal procedure from arrest to trial</li>
                    <li>Common criminal offenses</li>
                    <li>Victim rights and remedies</li>
                </ul>
                
                <h3>Speaker</h3>
                <p>Atty. Roberto Garcia, criminal defense lawyer with 20 years of experience in criminal litigation and former prosecutor.</p>
                
                <h3>Target Audience</h3>
                <p>General public, law students, security personnel, and anyone interested in understanding criminal law and procedure.</p>',
                'event_date' => now()->addDays(60)->setTime(14, 0),
                'location' => 'Quezon City Hall Auditorium, Quezon City',
                'meeting_link' => null,
                'max_participants' => 200,
                'registered_count' => 89,
            ],
        ];

        foreach ($events as $event) {
            Event::create([
                'title' => $event['title'],
                'event_type' => $event['event_type'],
                'description' => $event['description'],
                'content' => $event['content'],
                'event_date' => $event['event_date'],
                'location' => $event['location'],
                'meeting_link' => $event['meeting_link'],
                'max_participants' => $event['max_participants'],
                'registered_count' => $event['registered_count'],
                'is_published' => true,
                'views' => rand(30, 200),
            ]);
        }

        $this->command->info('Events seeded: ' . count($events));
    }

    private function seedGalleries()
    {
        $galleries = [
            [
                'title' => 'Legal Aid Mission 2025 - Tondo, Manila',
                'type' => 'photos',
                'description' => 'Photos from our free legal aid mission in Tondo, Manila where we provided consultations to over 200 residents.',
                'items' => [
                    ['title' => 'Registration Area', 'type' => 'image', 'order' => 1],
                    ['title' => 'Consultation Booths', 'type' => 'image', 'order' => 2],
                    ['title' => 'Volunteer Lawyers', 'type' => 'image', 'order' => 3],
                    ['title' => 'Community Engagement', 'type' => 'image', 'order' => 4],
                    ['title' => 'Document Review Session', 'type' => 'image', 'order' => 5],
                ],
            ],
            [
                'title' => 'Labor Law Seminar Highlights',
                'type' => 'videos',
                'description' => 'Video recordings from our comprehensive labor law seminar attended by over 150 participants.',
                'items' => [
                    ['title' => 'Opening Remarks', 'type' => 'video', 'order' => 1],
                    ['title' => 'Employee Rights Discussion', 'type' => 'video', 'order' => 2],
                    ['title' => 'Q&A Session', 'type' => 'video', 'order' => 3],
                ],
            ],
            [
                'title' => 'Small Business Legal Workshop 2026',
                'type' => 'photos',
                'description' => 'Highlights from our interactive workshop on legal compliance for small businesses.',
                'items' => [
                    ['title' => 'Workshop Venue', 'type' => 'image', 'order' => 1],
                    ['title' => 'Speaker Presentation', 'type' => 'image', 'order' => 2],
                    ['title' => 'Group Activities', 'type' => 'image', 'order' => 3],
                    ['title' => 'Networking Session', 'type' => 'image', 'order' => 4],
                ],
            ],
            [
                'title' => 'Family Law Forum - Cebu City',
                'type' => 'photos',
                'description' => 'Photos from our family law forum in Cebu City discussing marriage, separation, and inheritance.',
                'items' => [
                    ['title' => 'Panel of Speakers', 'type' => 'image', 'order' => 1],
                    ['title' => 'Audience Participation', 'type' => 'image', 'order' => 2],
                    ['title' => 'Open Forum', 'type' => 'image', 'order' => 3],
                    ['title' => 'Certificate Distribution', 'type' => 'image', 'order' => 4],
                ],
            ],
        ];

        foreach ($galleries as $galleryData) {
            $gallery = Gallery::create([
                'title' => $galleryData['title'],
                'type' => $galleryData['type'],
                'description' => $galleryData['description'],
                'is_published' => true,
                'views' => rand(20, 150),
            ]);

            foreach ($galleryData['items'] as $item) {
                $extension = $item['type'] === 'video' ? '.mp4' : '.jpg';
                GalleryItem::create([
                    'gallery_id' => $gallery->id,
                    'title' => $item['title'],
                    'file_path' => 'gallery/placeholder-' . strtolower(str_replace(' ', '-', $item['title'])) . $extension,
                    'order' => $item['order'],
                ]);
            }
        }

        $this->command->info('Galleries seeded: ' . count($galleries));
    }

    private function seedDownloadables()
    {
        $downloadables = [
            [
                'title' => 'Employment Contract Template',
                'category' => 'Labor Law',
                'description' => 'Standard employment contract template compliant with Philippine labor laws. Includes provisions for salary, benefits, working hours, and termination.',
                'file_type' => 'docx',
                'file_size' => 45000,
            ],
            [
                'title' => 'Deed of Absolute Sale - Real Property',
                'category' => 'Property Law',
                'description' => 'Template for deed of absolute sale for real property transactions. Includes all necessary provisions and ready for notarization.',
                'file_type' => 'docx',
                'file_size' => 38000,
            ],
            [
                'title' => 'Lease Agreement Template',
                'category' => 'Property Law',
                'description' => 'Comprehensive lease agreement template for residential and commercial properties. Covers rent, deposit, terms, and conditions.',
                'file_type' => 'docx',
                'file_size' => 42000,
            ],
            [
                'title' => 'Affidavit of Loss Template',
                'category' => 'Civil Law',
                'description' => 'Standard affidavit of loss template for lost documents, IDs, or property. Ready for notarization.',
                'file_type' => 'docx',
                'file_size' => 28000,
            ],
            [
                'title' => 'Special Power of Attorney Template',
                'category' => 'Civil Law',
                'description' => 'Template for special power of attorney for various transactions. Customizable for specific purposes.',
                'file_type' => 'docx',
                'file_size' => 35000,
            ],
            [
                'title' => 'Promissory Note Template',
                'category' => 'Business Law',
                'description' => 'Standard promissory note template for loans and credit transactions. Includes payment terms and interest provisions.',
                'file_type' => 'docx',
                'file_size' => 30000,
            ],
            [
                'title' => 'Non-Disclosure Agreement (NDA)',
                'category' => 'Business Law',
                'description' => 'Mutual and unilateral NDA templates for protecting confidential business information.',
                'file_type' => 'docx',
                'file_size' => 40000,
            ],
            [
                'title' => 'Demand Letter Template',
                'category' => 'Civil Law',
                'description' => 'Professional demand letter template for various claims including unpaid debts, contract breaches, and property disputes.',
                'file_type' => 'docx',
                'file_size' => 32000,
            ],
            [
                'title' => 'Complaint Affidavit Template',
                'category' => 'Criminal Law',
                'description' => 'Template for complaint affidavit to be filed with the prosecutor\'s office. Includes format and guidelines.',
                'file_type' => 'docx',
                'file_size' => 36000,
            ],
            [
                'title' => 'Business Partnership Agreement',
                'category' => 'Business Law',
                'description' => 'Comprehensive partnership agreement template covering capital contributions, profit sharing, management, and dissolution.',
                'file_type' => 'docx',
                'file_size' => 48000,
            ],
        ];

        foreach ($downloadables as $downloadable) {
            Downloadable::create([
                'title' => $downloadable['title'],
                'category' => $downloadable['category'],
                'description' => $downloadable['description'],
                'file_path' => 'downloadables/' . strtolower(str_replace(' ', '-', $downloadable['title'])) . '.' . $downloadable['file_type'],
                'file_type' => $downloadable['file_type'],
                'file_size' => $downloadable['file_size'],
                'is_published' => true,
                'downloads' => rand(50, 500),
            ]);
        }

        $this->command->info('Downloadables seeded: ' . count($downloadables));
    }
}
