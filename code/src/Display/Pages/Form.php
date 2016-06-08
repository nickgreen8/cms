<?php
namespace N8G\Grass\Display\Pages;

/**
 * This class is used to build a form page. All relevant data will be parsed ready to
 * be inserted into the page template. This will then be requested from the page and
 * output as is relevant.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Form extends PageAbstract
{
    
    /**
     * The name of the template to import
     * @var string
     */
    protected $template = 'form';

// Public functions

    /**
     * This function builds up the content for the page. Data is built into the page
     * data array for display when the page is rendered.
     *
     * @return void
     */
    public function build()
    {
        $this->container->get('logger')->info('Building page data');
        parent::build();

        //Get post data
        $form = $this->getFormData();

        //Add page data
        $this->addPageComponent('action', $form['action']);
        $this->addPageComponent('method', $form['method']);
        $this->addPageComponent('enctype', $form['enctype']);
        $this->addPageComponent('name', $form['name']);
        $this->addPageComponent('title', $form['title']);

        $this->addPageComponent('fields', $this->getFormFields($form['id']));
    }

// Private functions

    /**
     * Gets all the fields for the form and formats them into a usable format to output. The form ID is passed and an
     * array of data is returned.
     *
     * @param  integer $id ID of the form to get the fields for.
     * @return array       The field data to be output.
     */
    private function getFormFields($id)
    {
        //Get all the fields
        $fields = $this->getFormFieldData($id);
    }

    /**
     * This function gets the data related to the form ready for it to be output. The ID of the page is passed and the
     * data is returned as an array.
     *
     * @return array The form data to be output.
     */
    private function getFormData()
    {
        //Get the data
        $data = $this->container->get('db')->execProcedure('GetPageForm', array('page' => $this->id));
        return $data[0];
    }

    /**
     * This function gets the data related to the form fields ready for it to be output. The ID of the form is passed
     * and the data is returned as an array.
     *
     * @param  integer $id The ID of the form.
     * @return array       The form field data to be output.
     */
    private function getFormFieldData($id)
    {
        //Get the data
        $data = $this->container->get('db')->execProcedure('GetFormFields', array('form' => $id));
        return $data;
    }
}
