<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013-2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 *
 */

namespace qtism\runtime\rendering\markup\xhtml;

use qtism\data\ShufflableCollection;
use qtism\data\content\interactions\Orientation;
use qtism\runtime\rendering\markup\AbstractMarkupRenderingEngine;
use qtism\data\QtiComponent;
use \DOMDocumentFragment;

/**
 * ChoiceInteraction renderer. Rendered components will be transformed as
 * 'div' elements with 'qti-choiceInteraction' and 'qti-blockInteraction' additional CSS class.
 *
 * The following data-X attributes will be rendered:
 *
 * * data-responseIdentifier = qti:interaction->responseIdentifier
 * * data-shuffle = qti:choiceInteraction->shuffle
 * * data-max-choices = qti:choiceInteraction->maxChoices
 * * data-min-choices = qti:choiceInteraction->minChoices
 * * data-orientation = qti:choiceInteraction->orientation
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class ChoiceInteractionRenderer extends InteractionRenderer
{
    /**
     * Create a new ChoiceInteractionRenderer object.
     *
     * @param AbstractMarkupRenderingEngine $renderingEngine
     */
    public function __construct(AbstractMarkupRenderingEngine $renderingEngine = null)
    {
        parent::__construct($renderingEngine);
        $this->transform('div');
    }

    /**
     * @see \qtism\runtime\rendering\markup\xhtml\InteractionRenderer::appendAttributes()
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = '')
    {
        parent::appendAttributes($fragment, $component, $base);
        $this->additionalClass('qti-blockInteraction');
        $this->additionalClass('qti-choiceInteraction');
        $this->additionalUserClass(($component->getOrientation() === Orientation::VERTICAL) ? 'qti-vertical' : 'qti-horizontal');

        $fragment->firstChild->setAttribute('data-shuffle', ($component->mustShuffle() === true) ? 'true' : 'false');
        $fragment->firstChild->setAttribute('data-max-choices', $component->getMaxChoices());
        $fragment->firstChild->setAttribute('data-min-choices', $component->getMinChoices());
        $fragment->firstChild->setAttribute('data-orientation', ($component->getOrientation() === Orientation::VERTICAL) ? 'vertical' : 'horizontal');
    }

    /**
     * @see \qtism\runtime\rendering\markup\xhtml\AbstractXhtmlRenderer::appendChildren()
     */
    protected function appendChildren(DOMDocumentFragment $fragment, QtiComponent $component, $base = '')
    {
        parent::appendChildren($fragment, $component, $base);

        if ($this->getRenderingEngine()->mustShuffle() === true && $component->mustShuffle() === true) {
            Utils::shuffle($fragment->firstChild, new ShufflableCollection($component->getSimpleChoices()->getArrayCopy()));
        }
        
        // Put the choice elements into an unordered list.
        // Dev note: it seems we need a trick ... http://php.net/manual/en/domnode.removechild.php#90292
        $choiceElts = $fragment->firstChild->getElementsByTagName('li');
        $choiceQueue = array();
        $ulElt = $fragment->ownerDocument->createElement('ul');
        
        foreach ($choiceElts as $choiceElt) {
            $choiceQueue[] = $choiceElt;
        }
        
        foreach ($choiceQueue as $choiceElt) {
            $statements = Utils::extractStatements($choiceElt);
            $fragment->firstChild->removeChild($choiceElt);
            $ulElt->appendChild($choiceElt);
            
            if (empty($statements) === false) {
                $choiceElt->parentNode->insertBefore($statements[0], $choiceElt);
                $choiceElt->parentNode->insertBefore($statements[1], $choiceElt->nextSibling);
            }
        }
        
        $fragment->firstChild->appendChild($ulElt);
    }
}
