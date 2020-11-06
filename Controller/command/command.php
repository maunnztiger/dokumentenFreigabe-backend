<?php
namespace ISPComplaintsCRM\Controller\command;

abstract class Command {
    abstract public function execute(CommandContext $context):bool;
}