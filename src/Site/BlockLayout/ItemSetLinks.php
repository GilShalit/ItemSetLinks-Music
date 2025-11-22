<?php
namespace ItemSetLinks\Site\BlockLayout;

use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\View\Renderer\PhpRenderer;

class ItemSetLinks extends AbstractBlockLayout
{
    public function getLabel()
    {
        return 'Item Set Links'; // translatable
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
        $data = $block ? $block->data() : [];
        
        $form = new Form();
        
        // Item Set IDs field
        $itemSetIds = new Element\Textarea('o:block[__blockIndex__][o:data][item_set_ids]');
        $itemSetIds->setLabel('Item Set IDs');
        $itemSetIds->setAttribute('rows', 3);
        $itemSetIds->setValue($data['item_set_ids'] ?? '1303, 866, 605, 693, 1372, 558');
        $itemSetIds->setOption('info', 'Enter item set IDs separated by commas (e.g., 1303, 866, 605)');
        
        // Show as list field
        $showAsList = new Element\Textarea('o:block[__blockIndex__][o:data][show_as_list]');
        $showAsList->setLabel('Show as list');
        $showAsList->setAttribute('rows', 2);
        $showAsList->setValue($data['show_as_list'] ?? 'false, true, true, false, true, false');
        $showAsList->setOption('info', 'For each item set, enter "true" or "false" separated by commas. True will add &view=list to the URL.');
        
        // Description field - English
        $descriptionEn = new Element\Textarea('o:block[__blockIndex__][o:data][description_en]');
        $descriptionEn->setLabel('Description (English)');
        $descriptionEn->setAttribute('rows', 3);
        $descriptionEn->setValue($data['description_en'] ?? 'Database of "Music, Muslims and Jews", here you will find all the source materials and research outcomes of this project.');
        $descriptionEn->setOption('info', 'Description text in English. Leave empty to hide description.');
        
        // Description field - Hebrew
        $descriptionHe = new Element\Textarea('o:block[__blockIndex__][o:data][description_he]');
        $descriptionHe->setLabel('Description (Hebrew)');
        $descriptionHe->setAttribute('rows', 3);
        $descriptionHe->setAttribute('dir', 'rtl');
        $descriptionHe->setValue($data['description_he'] ?? '');
        $descriptionHe->setOption('info', 'Description text in Hebrew (עברית). Leave empty to use English version.');
        
        // Description field - Arabic
        $descriptionAr = new Element\Textarea('o:block[__blockIndex__][o:data][description_ar]');
        $descriptionAr->setLabel('Description (Arabic)');
        $descriptionAr->setAttribute('rows', 3);
        $descriptionAr->setAttribute('dir', 'rtl');
        $descriptionAr->setValue($data['description_ar'] ?? '');
        $descriptionAr->setOption('info', 'Description text in Arabic (العربية). Leave empty to use English version.');
        
        $form->add($itemSetIds);
        $form->add($showAsList);
        $form->add($descriptionEn);
        $form->add($descriptionHe);
        $form->add($descriptionAr);
        
        return $view->formCollection($form);
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        $data = $block->data();
        
        // Parse item set IDs
        $itemSetIdsString = $data['item_set_ids'] ?? '';
        $itemSetIds = array_map('trim', explode(',', $itemSetIdsString));
        $itemSetIds = array_filter($itemSetIds, 'is_numeric');
        
        // Parse show as list flags
        $showAsListString = $data['show_as_list'] ?? '';
        $showAsListArray = array_map('trim', explode(',', $showAsListString));
        $showAsListArray = array_map(function($val) {
            return strtolower($val) === 'true';
        }, $showAsListArray);
        
        // Get description based on current locale
        $locale = $view->lang();
        $description = '';
        
        // Map locale codes to description fields
        $localeMap = [
            'en' => 'description_en',
            'he' => 'description_he',
            'ar' => 'description_ar',
        ];
        
        // Try to get description for current locale
        if (isset($localeMap[$locale]) && !empty($data[$localeMap[$locale]])) {
            $description = $data[$localeMap[$locale]];
        } elseif (!empty($data['description_en'])) {
            // Fallback to English if current locale not found
            $description = $data['description_en'];
        }
        
        // Get site slug for URL building
        $siteSlug = $block->page()->site()->slug();
        
        // Get current locale
        $locale = $view->lang();
        
        // Fetch item sets
        $api = $view->api();
        $itemSetData = [];
        $counter = 1;
        $index = 0;
        
        foreach ($itemSetIds as $itemSetId) {
            $itemSetId = (int) $itemSetId;
            
            try {
                $itemSet = $api->read('item_sets', $itemSetId)->getContent();
                
                // Get the title in the current locale
                $title = $itemSet->displayTitle(null, $locale);
                
                // Build query parameters
                $queryParams = [
                    'sort_by' => 'title',
                    'sort_order' => 'asc',
                    'item_set_id' => [$itemSetId]
                ];
                
                // Add view=list if specified for this index
                if (isset($showAsListArray[$index]) && $showAsListArray[$index]) {
                    $queryParams['view'] = 'list';
                }
                
                $query = http_build_query($queryParams);
                $url = $view->basePath() . "/s/{$siteSlug}/item?{$query}";
                
                $itemSetData[] = [
                    'title' => $title,
                    'url' => $url,
                    'class' => 'home-link' . $counter
                ];
                
            } catch (\Exception $e) {
                // Skip item sets that don't exist or can't be accessed
                $index++;
                continue;
            }
            
            $counter++;
            $index++;
        }
        
        return $view->partial('common/block-layout/item-set-links', [
            'description' => $description,
            'itemSetData' => $itemSetData,
            'block' => $block,
        ]);
    }
}
