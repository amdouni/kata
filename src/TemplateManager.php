<?php

class TemplateManager
{
    private $placeholderHandler;

    public function __construct()
    {
        $this->placeholderHandler = new placeholderHandler();
    }

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if (!$quote) {
            return $tpl;
        }

        # Replace clone by HydrateTemplate. This helps getting more flexible and avoid some cloning troubles. cf. method
        $processedTemplate = $this->hydrateTemplate($tpl);

        # some names have been modified to better fit with the function
        $processedTemplate->subject = $this->processTextPlaceholder($processedTemplate->subject, $quote);
        $processedTemplate->content = $this->processTextPlaceholder($processedTemplate->content, $quote);

        # Even if the user is part of the text, I've preferred to process it in it's own method.
        # This way, I can handle some extra user processing in the future as the user is not simple placeholder.
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();
        $_user  = (isset($data['user'])  && ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

        $processedTemplate->content = $this->ProcessUserPlaceholder($processedTemplate->content, $_user);

        return $processedTemplate;
    }

    /**
     * @param $text
     * @param Quote $quote
     *
     * @return string
     */
    private function processTextPlaceholder($text, Quote $quote)
    {
        # I don't understand why we should get quote from repository instead of the quote we've received. Both solutions works
        # but getting quote from repository suppose that there was a prior persistence.
        $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());

        if(strpos($text, '[quote:destination_link]')){
            $destination = $quote->getDestination();
        }

        # Instead of developping the whole logic of managing placeholders in the template manager, I've created a placeHolderHandler
        # that can handle one or many placeholders and return a text with processed placeholders
        $placeholders = [
            '[quote:summary_html]' => QuoteRenderer::renderHtml($_quoteFromRepository->getId()),
            '[quote:summary]' => QuoteRenderer::renderText($_quoteFromRepository->getId()),
            '[quote:destination_name]' => $quote->getDestination()->countryName,
            '[quote:destination_link]' => $destination ? $quote->getSite()->url . '/' . $destination->countryName . '/quote/' . $quote->getId() : '',
        ];

        return $this->placeholderHandler->handleAllPlaceholders($placeholders, $text);
    }

    /**
     * @param $text
     * @param array $data
     *
     * @return string
     */
    public function processUserPlaceholder($text, $_user = null)
    {
        if(! $_user) {
            return $text;
        }

        return  $this->placeholderHandler->handlePlaceholder(
            '[user:first_name]',
            ucfirst(mb_strtolower($_user->firstname)),
            $text
        );
    }

    /**
     * @param Template $template
     *
     * @return Template
     */
    public function hydrateTemplate(Template $template)
    {
        /* https://dcsg.me/articles/dont-clone-your-php-objects-deepcopy-them/ */
        return new Template($template->id, $template->subject, $template->content);
    }
}
