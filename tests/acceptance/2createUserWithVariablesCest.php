<?php

use Codeception\Util\Locator;
use Helper\Acceptance;

/**
 * @method setUserName(string $string)

 */
class createUserWithVariablesCest extends BaseActions
{
    protected string $userNameNewUser = 'KateTester';
    protected ?string $emailNewUser = null;
    protected string $firstNameNewUser = 'FirstNameTester';
    protected string $lastNameNewUser = 'LastNameTester';
    protected ?int $globalID = null;
    protected ?int $newGlobalID = null;


    /** @var Acceptance|null Acceptance helper */
    protected ?Acceptance $helper = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        //throw new \Exception('gfcvh') ;
        $this->userNameNewUser = $this->userNameNewUser.(string)(random_int(1, 10000));
        $this->emailNewUser = "test".(string)(random_int(1, 10000))."@yopmail.com";
        $this->firstNameNewUser = $this->firstNameNewUser.(string)(random_int(1, 100));
        $this->lastNameNewUser = $this->lastNameNewUser.(string)(random_int(1, 10000));
        $this->globalID = $this->globalID.(random_int(1,1000));
        $this->newGlobalID = $this->newGlobalID.(random_int(1,1000));
    }
    // tests

    /**
     * @param AcceptanceTester $I
     * @throws \Codeception\Exception\ModuleException
     */

    public function loginToDash(AcceptanceTester $I)
    {
        $this->login($I);
    }


    /**
     * @param AcceptanceTester $I
     * @throws \Codeception\Exception\ModuleException
     * @throws Exception
     */
    public function createUser(AcceptanceTester $I)
    {
        $this->openUsersPage($I);
        $I->seeElement('//span[contains(text(),"Create new user")]');
        $I->click('//span[contains(text(),"Create new user")]');
        $I->wait(3);
        $I->click('//span[contains(text(),"Username")]/following::input[1]');
        $I->fillField('//span[contains(text(),"Username")]/following::input[1]', $this->userNameNewUser);
        $I->click('//span[contains(text(),"Email Address")]/following::input[1]');
        $I->fillField('//span[contains(text(),"Email Address")]/following::input[1]', $this->emailNewUser);
        $I->click('//span[contains(text(),"First Name")]/following::input[1]');
        $I->fillField('//span[contains(text(),"First Name")]/following::input[1]', $this->firstNameNewUser);
        $I->click('//span[contains(text(),"Last Name")]/following::input[1]');
        $I->fillField('//span[contains(text(),"Last Name")]/following::input[1]', $this->lastNameNewUser);
        $I->click('//span[contains(text(),"Global")]/following::input[1]');
        $I->fillField('//span[contains(text(),"Global")]/following::input[1]', $this->globalID);
        $I->wait(3);

        /*if ($I->dontSeePageHasElement("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_active']")) {
            $I->click("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_next']");
        }*/

        if ($I->tryToSeeElement("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_next']")) {
            $I->click("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_next']");
        }

        $arrayDepartments = $I->grabMultiple("//label[contains(text(), 'Select all')]");
        $sumDepartments = count($arrayDepartments);
        for($x=1; $x<=$sumDepartments; $x++) {
            $I->click("(//label[contains(text(), 'Select all')])["."$x"."]");
        }


        $arrayPermissions = $I->grabMultiple("//div[@class='ui-checkbox table-row-group__btns__checkbox ui-checkbox_default']");
        $sumPermission = count($arrayPermissions);
        for($d=1; $d<=$sumPermission; $d++){
            $I->click("(//div[@class='ui-checkbox table-row-group__btns__checkbox ui-checkbox_default'])[".$d."]");
        }


        $I->click("//span[contains(text(), 'Create')]");
        $I->wait(3);


        if ($I->tryToSeeElement("//div[contains(text(), 'Global ID cannot be linked to this user')]")){
            $I->click("//span[contains(text(), 'Ok')]");
            $I->clearField('//span[contains(text(),"Global")]/following::input[1]');
            $I->wait(3);
            $I->fillField('//span[contains(text(),"Global")]/following::input[1]', $this->newGlobalID);
            $I->click("//span[contains(text(), 'Create')]");
            $I->wait(3);
        }

        if ($I->tryToSeeElement("//div[contains(text(), 'This global ID does')]")) {
            $I->click("//span[contains(text(), 'Yes')]");
            $I->wait(5);
        }

        $I->see('User Info');

        $userNameValue = $I->grabValueFrom('//span[contains(text(),"Username")]/following::input[1]');
        $I->assertEquals($userNameValue, $this->userNameNewUser);

        $I->seeInField('//span[contains(text(),"Email Address")]/following::input[1]', $this->emailNewUser);
        $I->seeInField('//span[contains(text(),"First Name")]/following::input[1]', $this->firstNameNewUser);
        $I->seeInField('//span[contains(text(),"Last Name")]/following::input[1]', $this->lastNameNewUser);
        $I->seeInField('//span[contains(text(),"Global")]/following::input[1]', (string)$this->globalID);

        if ($I->tryToSeeElement("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_next']")) {
            $I->click("//li[@class='VTab__btn VTab__btn_MPCFilm VTab__btn_next']");
        }

        for($x=1; $x<=$sumDepartments; $x++) {
            $I->seeCheckboxIsChecked("(//div[contains(@class,'ui-checkbox table-content__column__item__select-all ui-checkbox_default')]/input)[".$x."]");
        }

        for($d=1; $d<=$sumPermission; $d++) {
            $I->seeCheckboxIsChecked("(//div[contains(@class,'ui-checkbox table-row-group__btns__checkbox ui-checkbox_default')]/input)[" . $d . "]");
        }
    }

    /**
     * @param AcceptanceTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function searchForCreatedUser(AcceptanceTester $I) {

        $this->openUsersPage($I);
        if ($I->tryToSeeElement("//li[contains(@class, 'VTab__btn_filter VTab__btn_active')]")){
            $I->click("//li[contains(@class, 'VTab__btn_search')]");
        }

        $I->fillField("//input[@class='search__input']", $this->userNameNewUser);
        $I->wait(3);
        $I->click("//button[@class='btn VButton']/child::span[contains(text(), 'Apply')]");
        $I->wait(3);
        $I->seeElement("//div[contains(text(), '"."$this->userNameNewUser"."')]");
        $I->seeElement("//div[contains(text(), '"."$this->emailNewUser"."')]");
    }

}