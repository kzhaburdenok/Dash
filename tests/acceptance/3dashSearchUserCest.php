<?php

use Codeception\Util\Locator;
use Helper\Acceptance;
class dashSearchUserCest extends BaseActions
{
    protected string $userNameNewUser = 'TesterTest1';
    protected string $emailNewUser = 'Tester1@yopmail.com';
    protected string $firstNameNewUser = 'FirstNameTester';
    protected string $lastNameNewUser = 'LastNameTester';
    protected int $globalID = 115;

    protected int $newGlobalID = 124;
    /** @var Acceptance|null Acceptance helper */
    protected ?Acceptance $helper = null;


   /* public function _before(AcceptanceTester $I)
    {

    }*/

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
     */
    public function searchForCreatedUser(AcceptanceTester $I){

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
