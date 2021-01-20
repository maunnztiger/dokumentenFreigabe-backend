<?php
namespace schoolyard\Controller\command;

abstract class Command {
    abstract public function execute(CommandContext $context):bool;
}