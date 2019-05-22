<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
        /**
         * @var Quote $quote
         */
        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->getId());
            $usefulObject = SiteRepository::getInstance()->getById($quote->getSite()->id);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->getDestination()->id);

            if(strpos($text, '[quote:destination_link]') !== false){
                $destination = DestinationRepository::getInstance()->getById($quote->getDestination()->id);
            }

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary     = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[quote:summary_html]',
                        QuoteRenderer::renderHtml($_quoteFromRepository),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[quote:summary]',
                        QuoteRenderer::renderText($_quoteFromRepository),
                        $text
                    );
                }
            }

            (strpos($text, '[quote:destination_name]') !== false) and $text = str_replace('[quote:destination_name]',$destinationOfQuote->countryName,$text);
        }

        if (isset($destination))
            $text = str_replace('[quote:destination_link]', $usefulObject->url . '/' . $destination->countryName . '/quote/' . $_quoteFromRepository->getId(), $text);
        else
            $text = str_replace('[quote:destination_link]', '', $text);

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($_user->firstname)), $text);
        }

        return $text;
    }
}
