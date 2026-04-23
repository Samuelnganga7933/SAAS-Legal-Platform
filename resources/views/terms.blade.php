@extends('layouts.app')

@section('title', 'Terms of Service - Le Nium Advisors')

@section('content')

<section class="py-20 px-4 bg-white">
    <div class="container mx-auto max-w-4xl pt-32">
        <h1 class="text-5xl font-bold text-slate-900 mb-8">Terms of Service</h1>

        <div class="prose prose-lg max-w-none text-gray-700 space-y-6">
            <h2 class="text-2xl font-bold text-slate-900 mt-8">1. Important Disclaimer</h2>
            <p>
                <strong>Le Nium Advisors provides informational support and guidance only.</strong> We are NOT a regulated law firm or licensed legal practice. The information, guidance, and resources provided through our services should not be considered legal, tax, financial, or investment advice.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">2. Informational Services Only</h2>
            <p>
                Le Nium Advisors ("we," "us," "our," or "Company") provides informational support, guidance resources, and procedural help across legal operations and accounting domains. All services are provided for informational and educational purposes only. The information on this website and in our services is not a substitute for professional legal, tax, financial, or investment advice.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">3. No Legal Advice</h2>
            <p>
                The information provided by Le Nium Advisors is not legal advice. Any information, opinions, or recommendations provided should not be used as a substitute for consulting with a qualified attorney who has agreed to represent you for the specific matter you are addressing. We do not provide legal advice in any jurisdiction.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">4. No Attorney-Client Relationship</h2>
            <p>
                By using our services or accessing our website, and absent a written engagement agreement, no attorney-client relationship is created between you and Le Nium Advisors. If we provide consultation services, an attorney-client relationship will only be created upon execution of a written engagement agreement.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">5. Regulated Services</h2>
            <p>
                Le Nium Advisors is not a regulated law firm and does not claim to be a licensed legal, accounting, or financial services provider. You should not rely on our services as a substitute for professional regulated services from qualified practitioners in their respective jurisdictions.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">6. Limitation of Liability</h2>
            <p>
                Le Nium Advisors shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of our services or website, including but not limited to loss of profits, business interruption, loss of data, or other intangible losses, regardless of the cause and whether arising under contract, tort, strict liability, or any other legal theory.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">7. No Guarantees</h2>
            <p>
                We do not guarantee any specific results from our advisory services. Past performance, outcomes, or results are not indicative of future performance. Trading and investment activities carry significant risks, including the potential loss of principal. Our guidance is based on general information and should not be considered a guarantee of outcomes.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">8. Your Responsibility</h2>
            <p>
                You are responsible for:
            </p>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Ensuring that your use of our services complies with all applicable laws, regulations, and rules in your jurisdiction</li>
                <li>Seeking professional legal, tax, and financial advice before making any important business or personal decisions</li>
                <li>Verifying all information provided and conducting your own due diligence</li>
                <li>Understanding that informational resources may not cover all scenarios or local variations</li>
            </ul>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">9. Jurisdictional Limitations</h2>
            <p>
                Le Nium Advisors operates internationally but is limited by relevant jurisdictional laws and regulations. We cannot provide services or advice that would constitute practice of law in jurisdictions where we are not licensed or authorized to practice. Users from specific jurisdictions may be restricted from accessing certain services.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">10. Reviews and Testimonials</h2>
            <p>
                Reviews and testimonials from our clients reflect individual experiences. Results may vary based on individual circumstances, jurisdiction, and specific needs. We do not claim regulated legal outcomes or guaranteed results. Clients' experiences do not represent a promise or guarantee of your experience.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">11. Changes and Updates</h2>
            <p>
                We reserve the right to modify, update, or change our services, website, or any information provided at any time without prior notice. Your continued use of our services indicates acceptance of these changes.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">12. Third-Party Content</h2>
            <p>
                This website may contain links to third-party websites. Le Nium Advisors is not responsible for the content, accuracy, or practices of these external websites. Your access and use of third-party websites is at your own risk and subject to their terms and conditions.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">13. Contact Information</h2>
            <p>
                If you have questions about these Terms of Service or our services, please contact us at:
            </p>
            <ul class="list-none space-y-2">
                <li><strong>Email:</strong> leniumtradinggroup@outlook.com</li>
                <li><strong>WhatsApp:</strong> +254 104 921 009</li>
            </ul>

            <div class="bg-amber-50 border-l-4 border-amber-400 p-6 mt-8">
                <p class="font-semibold text-amber-900">
                    These Terms of Service are effective as of {{ now()->format('F d, Y') }} and may be updated at any time. Your continued use of our services indicates your acceptance of the current terms.
                </p>
            </div>
        </div>

        <div class="mt-12">
            <a href="/" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</section>

@endsection
