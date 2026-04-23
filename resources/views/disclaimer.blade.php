@extends('layouts.app')

@section('title', 'Disclaimer: Le Nium Advisors')

@section('content')

<section class="py-20 px-4 bg-white">
    <div class="container mx-auto max-w-4xl pt-32">
        <h1 class="text-5xl font-bold text-slate-900 mb-8">Legal Disclaimer</h1>

        <div class="prose prose-lg max-w-none text-gray-700 space-y-6">
            <h2 class="text-2xl font-bold text-slate-900 mt-8">1. General Information</h2>
            <p>
                Le Nium Advisors ("we," "us," "our," or "Company") provides legal advisory and consulting services. The information on this website and in our services is provided for general informational and educational purposes only. It is not a substitute for professional legal, tax, financial, or investment advice.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">2. No Legal Advice</h2>
            <p>
                The information provided by Le Nium Advisors is not legal advice. Any information, opinions, or recommendations provided should not be used as a substitute for consulting with a qualified attorney who has agreed to represent you for the specific matter you are addressing.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">3. No Attorney-Client Relationship</h2>
            <p>
                By using our services or accessing our website, and absent a written engagement agreement, no attorney-client relationship is created between you and Le Nium Advisors. If we provide consultation services, an attorney-client relationship will only be created upon execution of a written engagement agreement.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">4. Limitation of Liability</h2>
            <p>
                Le Nium Advisors shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of our services or website, including but not limited to loss of profits, business interruption, loss of data, or other intangible losses, regardless of the cause and whether arising under contract, tort, strict liability, or any other legal theory.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">5. No Guarantees</h2>
            <p>
                We do not guarantee any specific results from our advisory services. Past performance, outcomes, or results are not indicative of future performance. Trading and investment activities carry significant risks, including the potential loss of principal.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">6. Jurisdictional Limitations</h2>
            <p>
                Le Nium Advisors operates internationally but is limited by relevant jurisdictional laws and regulations. We cannot provide services or advice that would constitute practice of law in jurisdictions where we are not licensed or authorized to practice.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">7. Changes and Updates</h2>
            <p>
                We reserve the right to modify, update, or change our services, website, or any information provided at any time without prior notice. Your continued use of our services indicates acceptance of these changes.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">8. Third-Party Content</h2>
            <p>
                This website may contain links to third-party websites. Le Nium Advisors is not responsible for the content, accuracy, or practices of these external websites. Your access and use of third-party websites is at your own risk and subject to their terms and conditions.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">9. Compliance with Laws</h2>
            <p>
                You are responsible for ensuring that your use of our services complies with all applicable laws, regulations, and rules in your jurisdiction. Le Nium Advisors assumes no responsibility for your non-compliance with applicable laws.
            </p>

            <h2 class="text-2xl font-bold text-slate-900 mt-8">10. Contact Information</h2>
            <p>
                If you have questions about this disclaimer or our services, please contact us at:
            </p>
            <ul class="list-none space-y-2">
                <li><strong>Email:</strong> leniumtradinggroup@outlook.com</li>
                <li><strong>WhatsApp:</strong> +254 104 921 009</li>
            </ul>

            <div class="bg-amber-50 border-l-4 border-amber-400 p-6 mt-8">
                <p class="font-semibold text-amber-900">
                    This disclaimer is effective as of {{ now()->format('F d, Y') }} and may be updated at any time. Your continued use of our services indicates your acceptance of the current disclaimer.
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
